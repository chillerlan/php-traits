<?php
/**
 * Trait MemzeroDestructorTrait
 *
 * @filesource   MemzeroDestructorTrait.php
 * @created      25.01.2018
 * @package      chillerlan\Traits\Crypto
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\Crypto;

trait MemzeroDestructorTrait{

	/**
	 * @return void
	 */
	public function __destruct(){

		if(!function_exists('sodium_memzero')){
			return; // @codeCoverageIgnore
		}

		foreach(array_keys(get_object_vars($this)) as $key){

			if(is_scalar($this->{$key})){
				$this->{$key} = (string)$this->{$key};

				sodium_memzero($this->{$key});
			}

			unset($this->{$key});
		}

	}


}
