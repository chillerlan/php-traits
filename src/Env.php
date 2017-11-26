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
 */
trait Env{

	/**
	 * a backup environment in case everything goes downhill
	 *
	 * @var array
	 */
	private $_ENV;

	/**
	 * @param string      $path
	 * @param string|null $filename
	 * @param bool|null   $overwrite
	 * @param array|null  $required
	 *
	 * @return $this
	 */
	protected function __loadEnv(string $path, string $filename = null, bool $overwrite = null, array $required = null){
		$overwrite = $overwrite !== null ? $overwrite : false;
		$content   = $this->__read(rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.($filename ?? '.env'));

		return $this
			->__load($content, $overwrite)
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

		if(array_key_exists($var, $_ENV)){
			return $_ENV[$var];
		}
		elseif(function_exists('getenv')){
			if($e = getenv($var) !== false){
				return $e;
			}
		}
		// @codeCoverageIgnoreStart
		elseif(function_exists('apache_getenv')){
			if($e = apache_getenv($var) !== false){
				return $e;
			}
		}
		// @codeCoverageIgnoreEnd

		return $this->_ENV[$var] ?? false;
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

		putenv($var.'='.$value);

		// fill $_ENV explicitly, assuming variables_order="GPCS" (production)
		$_ENV[$var] = $value;
		// a backup
		$this->_ENV[$var] = $value;

		if(function_exists('apache_setenv')){
			apache_setenv($var, $value);
		}

		return $this;
	}

	/**
	 * @param string $var
	 *
	 * @return $this
	 */
	protected function __unsetEnv(string $var){
		$var = strtoupper($var);

		unset($_ENV[$var]);
		unset($this->_ENV[$var]);
		putenv($var);

		return $this;
	}

	/**
	 * use with caution!
	 *
	 * @return $this
	 */
	protected function __clearEnv(){
		$_ENV       = [];
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
	 * @link http://php.net/variables-order
	 *
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
			if(empty($kv[0]) || is_numeric($kv[0]) || strpos($kv[0], ' ') !== false || (!$overwrite && array_key_exists($kv[0], $_ENV))){
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
				$value = preg_replace_callback('/\${([_a-z\d]+)}/i', function($matches){
					return $this->__getEnv($matches[1]);
				}, $value);
			}

		}

		return $value;
	}

	/**
	 * @param array|null $required
	 *
	 * @return $this
	 * @throws \chillerlan\Traits\TraitException
	 */
	private function __check(array $required = null){

		if($required === null || empty($required)){
			return $this;
		}

		foreach($required as $var){
			if(!$this->__getEnv($var) || $this->__getEnv($var) === null){
				throw new TraitException('required variable not set: '.strtoupper($var));
			}
		}

		return $this;
	}
}
