<?php
/**
 * Trait DotArray
 *
 * @filesource   DotArray.php
 * @created      13.11.2017
 * @package      chillerlan\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits;

/**
 * @link https://github.com/laravel/framework/blob/5.4/src/Illuminate/Support/Arr.php
 */
trait DotArray{

	/**
	 *  Checks if $key isset in $array using dot notation and returns it on success.
	 *
	 * @param array  $array   the array to search in
	 * @param string $key     the key to search
	 * @param mixed  $default [optional] a default value in case the key isn't being found
	 *
	 * @return mixed returns $array[$key], $default otherwise.
	 */
	protected function array_get(array $array, string $key, $default = null){

		if(isset($array[$key])){
			return $array[$key];
		}

		foreach(explode('.', $key) as $segment){

			if(!is_array($array) || !array_key_exists($segment, $array)){
				return $default;
			}

			$array = $array[$segment];
		}

		return $array;
	}

	/**
	 * Checks if $key exists in $array using dot notation and returns it on success
	 *
	 * @param array  $array the array to search in
	 * @param string $key   the key to search
	 *
	 * @return bool
	 */
	protected function array_in(array $array, string $key):bool{

		if(empty($array)){
			return false;
		}

		if(array_key_exists($key, $array)){
			return true;
		}

		foreach(explode('.', $key) as $segment){

			if(!is_array($array) || !array_key_exists($segment, $array)){
				return false;
			}

			$array = $array[$segment];
		}

		return true;
	}

	/**
	 * Sets $key in $array using dot notation
	 *
	 * If no key is given to the method, the entire array will be replaced.
	 *
	 * @param  array  $array
	 * @param  string $key
	 * @param  mixed  $value
	 *
	 * @return array
	 */
	protected function array_set(array &$array, string $key, $value){

		if(is_null($key)){
			return $array = $value;
		}

		$keys = explode('.', $key);

		while(count($keys) > 1){
			$key = array_shift($keys);

			// If the key doesn't exist at this depth, we will just create an empty array
			// to hold the next value, allowing us to create the arrays to hold final
			// values at the correct depth. Then we'll keep digging into the array.
			if(!isset($array[$key]) || !is_array($array[$key])){
				$array[$key] = [];
			}

			$array = &$array[$key];
		}

		$array[array_shift($keys)] = $value;

		return $array;
	}

}
