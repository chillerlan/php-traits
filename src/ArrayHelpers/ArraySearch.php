<?php
/**
 * Trait ArraySearch
 *
 * @filesource   ArraySearch.php
 * @created      04.12.2017
 * @package      chillerlan\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits;

use ArrayIterator, ArrayObject, RecursiveArrayIterator, RecursiveIteratorIterator, Traversable;

class ArraySearch{

	/**
	 * @var \IteratorIterator|\RecursiveIteratorIterator
	 */
	protected $iterator;

	/**
	 * @var array
	 */
	protected $array;

	/**
	 * ExtendedIteratorTrait constructor.
	 *
	 * @param array|object|\Traversable|\ArrayIterator|\ArrayObject|null $array
	 */
	public function __construct($array = null){

		if(($array instanceof ArrayObject) || ($array instanceof ArrayIterator)){
			$this->array = $array->getArrayCopy();
		}
		elseif($array instanceof Traversable){
			$this->array = iterator_to_array($array);
		}
		elseif(gettype($array) === 'object'){
			$this->array = get_object_vars($array);
		}
		elseif(is_array($array)){
			$this->array = $array;
		}
		else{
			$this->array = [];
		}

	}

	/**
	 * @param string $dotKey
	 *
	 * @return mixed
	 */
	public function arraySearch(string $dotKey){
		$this->iterator = $this->getRecursiveIteratorIterator();

		foreach($this->iterator as $v){

			if($this->getPath() === $dotKey){
				return $v;
			}

		}

		return null;
	}

	/**
	 * @param string $dotKey
	 *
	 * @return bool
	 */
	public function arrayIsset(string $dotKey):bool{
		$this->iterator = $this->getRecursiveIteratorIterator();

		foreach($this->iterator as $v){

			if($this->getPath() === $dotKey){
				return true;
			}

		}

		return false;
	}

	/**
	 * @return \RecursiveIteratorIterator
	 */
	private function getRecursiveIteratorIterator():RecursiveIteratorIterator{
		return new RecursiveIteratorIterator(new RecursiveArrayIterator($this->array), RecursiveIteratorIterator::SELF_FIRST);
	}

	/**
	 * @return string
	 */
	private function getPath():string{
		return implode('.', array_map(function(int $depth):string {
			return $this->iterator->getSubIterator($depth)->key();
		}, range(0, $this->iterator->getDepth())));
	}

}
