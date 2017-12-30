<?php
/**
 * Trait DotArray
 *
 * @filesource   DotArray.php
 * @created      13.11.2017
 * @package      chillerlan\Traits\ArrayHelpers
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\ArrayHelpers;

/**
 * @link https://github.com/laravel/framework/blob/5.4/src/Illuminate/Support/Arr.php
 */
trait DotArray{

	/**
	 * @var array
	 */
	protected $array;

	/**
	 *  Checks if $key isset in $array using dot notation and returns it on success.
	 *
	 * @param string $dotKey  the key to search
	 * @param mixed  $default [optional] a default value in case the key isn't being found
	 *
	 * @return mixed returns $array[$key], $default otherwise.
	 */
	public function get(string $dotKey, $default = null){

		if(isset($this->array[$dotKey])){
			return $this->array[$dotKey];
		}

		$array = &$this->array;

		foreach(explode('.', $dotKey) as $segment){

			if(!is_array($array) || !array_key_exists($segment, $array)){
				return $default;
			}

			$array = &$array[$segment];
		}

		return $array;
	}

	/**
	 * Checks if $key exists in $array using dot notation and returns it on success
	 *
	 * @param string $dotKey the key to search
	 *
	 * @return bool
	 */
	public function in(string $dotKey):bool{

		if(empty($this->array)){
			return false;
		}

		if(array_key_exists($dotKey, $this->array)){
			return true;
		}

		$array = &$this->array;

		foreach(explode('.', $dotKey) as $segment){

			if(!is_array($array) || !array_key_exists($segment, $array)){
				return false;
			}

			$array = &$array[$segment];
		}

		return true;
	}

	/**
	 * Sets $key in $array using dot notation
	 *
	 * If no key is given to the method, the entire array will be replaced.
	 *
	 * @param  string $dotKey
	 * @param  mixed  $value
	 *
	 * @return \chillerlan\Traits\ArrayHelpers\DotArray
	 */
	public function set(string $dotKey, $value){

		if(empty($dotKey)){
			$this->array = $value;

			return $this;
		}

		$array = &$this->array;
		$keys = explode('.', $dotKey);

		while(count($keys) > 1){
			$dotKey = array_shift($keys);

			// If the key doesn't exist at this depth, we will just create an empty array
			// to hold the next value, allowing us to create the arrays to hold final
			// values at the correct depth. Then we'll keep digging into the array.
			if(!isset($array[$dotKey]) || !is_array($array[$dotKey])){
				$array[$dotKey] = [];
			}

			$array = &$array[$dotKey];
		}

		$array[array_shift($keys)] = $value;

		return $this;
	}

}
