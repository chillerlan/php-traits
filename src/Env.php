<?php
/**
 * Trait Env
 *
 * @filesource   Env.php
 * @created      25.11.2017
 * @package      chillerlan\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits;

/**
 * Loads .env config files into the environment
 *
 * $_ENV > getenv()!
 *
 * @link https://github.com/vlucas/phpdotenv
 * @link http://php.net/variables-order
 *
 */
trait Env{

	/**
	 * a backup environment in case everything goes downhill
	 *
	 * @var array
	 */
	private $_ENV = [];

	/**
	 * Sets the global $_ENV if true. Otherwise all variables are being kept internally
	 * in $this->_ENV to avoid leaks, making them only accessible via Env::__getEnv().
	 *
	 * @var bool
	 */
	private $_global;

	/**
	 * @param string      $path
	 * @param string|null $filename
	 * @param bool|null   $overwrite
	 * @param array|null  $required
	 * @param bool|null   $global
	 *
	 * @return $this
	 */
	protected function __loadEnv(string $path, string $filename = null, bool $overwrite = null, array $required = null, bool $global = null){
		$this->_global = $global ?? false;
		$content       = $this->__read(rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.($filename ?? '.env'));

		return $this
			->__load($content, $overwrite ?? false)
			->__check($required)
		;
	}

	/**
	 * @param string $var
	 *
	 * @return bool|mixed
	 */
	protected function __getEnv(string $var){
		$var = strtoupper($var);
		$env = null;

		if($this->_global === true){

			if(array_key_exists($var, $_ENV)){
				$env = $_ENV[$var];
			}
			elseif(function_exists('getenv')){
				$env = getenv($var);
			}
			// @codeCoverageIgnoreStart
			elseif(function_exists('apache_getenv')){
				$env = apache_getenv($var);
			}
			// @codeCoverageIgnoreEnd

		}

		return $env ?? $this->_ENV[$var] ?? false;
	}

	/**
	 * @param string $var
	 * @param string $value
	 *
	 * @return $this
	 */
	protected function __setEnv(string $var, string $value = null){
		$var   = strtoupper($var);
		$value = $this->__parse($value);

		if($this->_global === true){
			putenv($var.'='.$value);

			// fill $_ENV explicitly, assuming variables_order="GPCS" (production)
			$_ENV[$var] = $value;

			// @codeCoverageIgnoreStart
			if(function_exists('apache_setenv')){
				apache_setenv($var, $value);
			}
			// @codeCoverageIgnoreEnd
		}

		// a backup
		$this->_ENV[$var] = $value;

		return $this;
	}

	/**
	 * @param string $var
	 *
	 * @return bool
	 */
	protected function __issetEnv(string $var):bool {
		return
			($this->_global && (
				isset($_ENV[$var])
				|| getenv($var)
				|| (function_exists('apache_getenv') && apache_getenv($var))
			))
			|| array_key_exists($var, $this->_ENV);
	}

	/**
	 * @param string $var
	 *
	 * @return $this
	 */
	protected function __unsetEnv(string $var){
		$var = strtoupper($var);

		if($this->_global === true){
			unset($_ENV[$var]);
			putenv($var);
		}

		unset($this->_ENV[$var]);

		return $this;
	}

	/**
	 * use with caution!
	 *
	 * @return $this
	 */
	protected function __clearEnv(){

		if($this->_global === true){
			$_ENV = [];
		}

		$this->_ENV = [];

		return $this;
	}

	/**
	 * @param string $file
	 *
	 * @return array
	 * @throws \chillerlan\Traits\TraitException
	 */
	private function __read(string $file):array{

		if(!is_readable($file) || !is_file($file)){
			throw new TraitException('invalid file: '.$file);
		}

		// Read file into an array of lines with auto-detected line endings
		$autodetect = ini_get('auto_detect_line_endings');
		ini_set('auto_detect_line_endings', '1');
		$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		ini_set('auto_detect_line_endings', $autodetect);

		if(!is_array($lines) || empty($lines)){
			throw new TraitException('error while reading file: '.$file);
		}

		return array_map('trim', $lines);
	}

	/**
	 * @param array $data
	 * @param bool  $overwrite
	 *
	 * @return $this
	 */
	private function __load(array $data, bool $overwrite){

		foreach($data as $line){

			// skip empty lines and comments
			if(empty($line) || strpos($line, '#') === 0){
				continue;
			}

			$kv = array_map('trim', explode('=', $line, 2));

			// skip empty and numeric keys, keys with spaces, existing keys that shall not be overwritten
			if(empty($kv[0]) || is_numeric($kv[0]) || strpos($kv[0], ' ') !== false || (!$overwrite && $this->__getEnv($kv[0]) !== false)){
				continue;
			}

			$this->__setEnv($kv[0], isset($kv[1]) ? trim($kv[1]) : null);
		}

		return $this;
	}

	/**
	 * @param string $value
	 *
	 * @return string|null
	 */
	private function __parse(string $value = null){

		if($value !== null){

			$q = $value[0] ?? null;

			$value = in_array($q, ["'", '"'], true)
				// handle quoted strings
				? preg_replace("/^$q((?:[^$q\\\\]|\\\\\\\\|\\\\$q)*)$q.*$/mx", '$1', $value)
				// skip inline comments
				: trim(explode('#', $value, 2)[0]);

			// handle multiline values
			$value = implode(PHP_EOL, explode('\\n', $value));

			// handle nested ${VARS}
			if(strpos($value, '$') !== false){
				$value = preg_replace_callback('/\${(?<var>[_a-z\d]+)}/i', function($matches){
					return $this->__getEnv($matches['var']);
				}, $value);
			}

		}

		return $value;
	}

	/**
	 * @param string[]|null $required - case sensitive!
	 *
	 * @return $this
	 * @throws \chillerlan\Traits\TraitException
	 */
	private function __check(array $required = null){

		if($required === null || empty($required)){
			return $this;
		}

		foreach($required as $var){
			if(!$this->__issetEnv($var)){
				throw new TraitException('required variable not set: '.strtoupper($var));
			}
		}

		return $this;
	}
}
