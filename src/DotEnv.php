<?php
/**
 * Class DotEnv
 *
 * @filesource   DotEnv.php
 * @created      25.11.2017
 * @package      chillerlan\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits;

class DotEnv{
	use Env;

	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @var string
	 */
	protected $filename;

	/**
	 * DotEnv constructor.
	 *
	 * @param string      $path
	 * @param string|null $filename
	 */
	public function __construct(string $path, string $filename = null){
		$this->path     = $path;
		$this->filename = $filename;
	}

	/**
	 * @param array|null $required
	 *
	 * @return \chillerlan\Traits\DotEnv
	 */
	public function load(array $required = null):DotEnv{
		return $this->__loadEnv($this->path, $this->filename, true, $required);
	}

	/**
	 * @param string      $path
	 * @param string|null $filename
	 * @param bool|null   $overwrite
	 * @param array|null  $required
	 *
	 * @return \chillerlan\Traits\DotEnv
	 */
	public function addEnv(string $path, string $filename = null, bool $overwrite = null, array $required = null):DotEnv{
		return $this->__loadEnv($path, $filename, $overwrite, $required);
	}

	/**
	 * @param string $var
	 *
	 * @return bool|mixed
	 */
	public function get(string $var){
		return $this->__getEnv($var);
	}

	/**
	 * @param string $var
	 * @param string $value
	 *
	 * @return $this
	 */
	public function set(string $var, string $value){
		return $this->__setEnv($var, $value);
	}

	/**
	 * @param string $var
	 *
	 * @return \chillerlan\Traits\DotEnv
	 */
	public function unset(string $var):DotEnv{
		return $this->__unsetEnv($var);
	}

	/**
	 * @return \chillerlan\Traits\DotEnv
	 */
	public function clear():DotEnv{
		return $this->__clearEnv();
	}

}
