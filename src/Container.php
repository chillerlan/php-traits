<?php
/**
 * Trait Container
 *
 * @filesource   Container.php
 * @created      13.11.2017
 * @package      chillerlan\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits;

use ReflectionProperty;

/**
 * a generic container with magic getter and setter
 */
trait Container{

	/**
	 * @param array $properties
	 */
	public function __construct(array $properties = []){

		foreach($properties as $key => $value){
			$this->__set($key, $value);
		}

	}

	/**
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function __get(string $property){

		if(property_exists($this, $property) && !(new ReflectionProperty($this, $property))->isPrivate()){
			return $this->{$property};
		}

		return null;
	}

	/**
	 * @param string $property
	 * @param mixed  $value
	 *
	 * @return void
	 */
	public function __set(string $property, $value){

		if(property_exists($this, $property) && !(new ReflectionProperty($this, $property))->isPrivate()){
			$this->{$property} = $value;
		}

	}

	/**
	 * @return array
	 */
	public function __toArray():array {
		$data = [];

		foreach($this as $key => $value){
			$data[$key] = $value;
		}

		return $data;
	}

}
