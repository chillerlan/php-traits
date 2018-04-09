<?php
/**
 * Trait SearchableArray
 *
 * @filesource   SearchableArray.php.php
 * @created      04.12.2017
 * @package      chillerlan\Traits\ArrayHelpers
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\ArrayHelpers;

use ArrayIterator, ArrayObject, RecursiveArrayIterator, RecursiveIteratorIterator, Traversable;

trait SearchableArray{
	use DotArray;

	/**
	 * @var \IteratorIterator|\RecursiveIteratorIterator
	 */
	protected $iterator;

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
		// yields unexpected results with DotArray
		elseif(gettype($array) === 'object'){
			$this->array = get_object_vars($array);
		}
		elseif(is_array($array)){
			$this->array = $array;
		}

		$this->iterator = new RecursiveIteratorIterator(
			new RecursiveArrayIterator($this->array),
			RecursiveIteratorIterator::SELF_FIRST
		);
	}

	/**
	 * @param string $dotKey
	 *
	 * @return mixed
	 */
	public function searchByKey(string $dotKey){

		foreach($this->iterator as $v){

			if($this->getPath() === $dotKey){
				return $v;
			}

		}

		return null;
	}

	public function searchByValue($value):array {

		$matches = [];

		foreach($this->iterator as $v){

			if($v === $value){
				$matches[$this->getPath()] = $value;
			}

		}

		return $matches;
	}

	/**
	 * @param string $dotKey
	 *
	 * @return bool
	 */
	public function isset(string $dotKey):bool{

		foreach($this->iterator as $v){

			if($this->getPath() === $dotKey){
				return true;
			}

		}

		return false;
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
