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

/**
 * @method bool|mixed __get(string $var)
 * @method bool|mixed get(string $var)
 * @method void __set(string $var, string $value = null)
 * @method DotEnv set(string $var, string $value = null)
 * @method bool __isset(string $var)
 * @method void __unset(string $var)
 * @method bool isset(string $var)
 * @method void unset(string $var)
 * @method DotEnv clear()
 */
class DotEnv{
	use Env{
		// allow a magic getter & setter
		__getEnv as public __get;
		__setEnv as public __set;
		// as well as a generic ones
		__getEnv as public get;
		__setEnv as public set;
		// magic isset & unset
		__issetEnv as public __isset;
		__unsetEnv as public __unset;
		// generic isset, unset & clear
		__issetEnv as public isset;
		__unsetEnv as public unset;
		__clearEnv as public clear;
	}

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
	 * @param bool|null   $global
	 */
	public function __construct(string $path, string $filename = null, bool $global = null){
		$this->path     = $path;
		$this->filename = $filename;
		$this->_global  = $global ?? true; // emulate vlucas/dotenv behaviour by default
	}

	/**
	 * @param array|null $required
	 *
	 * @return \chillerlan\Traits\DotEnv
	 */
	public function load(array $required = null):DotEnv{
		return $this->__loadEnv($this->path, $this->filename, true, $required, $this->_global);
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
		return $this->__loadEnv($path, $filename, $overwrite, $required, $this->_global);
	}

}
