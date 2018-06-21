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

use ReflectionClass, ReflectionProperty;

/**
 * a generic container with magic getter and setter
 */
trait Container{

	/**
	 * @param iterable $properties
	 */
	public function __construct(array $properties = null){

		if(!empty($properties)){
			$this->__fromIterable($properties);
		}

		// call a method with trait name as replacement constructor for each trait
		$traits = (new ReflectionClass($this))->getTraits();

		foreach($traits as $trait){
			$method = $trait->getShortName();

			if(method_exists($this, $method)){
				call_user_func([$this, $trait->getShortName()]);
			}
		}
	}

	/**
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function __get(string $property){

		if($this->__isset($property)){
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

		// avoid overwriting private properties
		if(property_exists($this, $property) && !$this->__isPrivate($property)){
			$this->{$property} = $value;
			return;
		}

		return; // should not see me
	}

	/**
	 * @param string $property
	 *
	 * @return bool
	 */
	public function __isset(string $property):bool{
		return (isset($this->{$property}) && !$this->__isPrivate($property));
	}

	/**
	 * @param string $property
	 *
	 * @return bool
	 */
	protected function __isPrivate(string $property):bool{
		return (new ReflectionProperty($this, $property))->isPrivate();
	}

	/**
	 * @param string $property
	 *
	 * @return void
	 */
	public function __unset(string $property){

		// avoid unsetting private properties
		if($this->__isset($property)){
			unset($this->{$property});
		}

	}

	/**
	 * @return string
	 */
	public function __toString():string{
		return $this->__toJSON();
	}

	/**
	 * @return array
	 */
	public function __toArray():array{
		$data = [];

		foreach($this as $property => $value){

			// exclude private properties
			if($this->__isset($property)){
				$data[$property] = $value;
			}

		}

		return $data;
	}

	/**
	 * @param iterable $properties
	 *
	 * @return $this
	 */
	public function __fromIterable(array $properties){

		foreach($properties as $key => $value){
			$this->__set($key, $value);
		}

		return $this;
	}

	/**
	 * @param bool|null $prettyprint
	 *
	 * @return string
	 */
	public function __toJSON(bool $prettyprint = null):string{
		return json_encode($this->__toArray(), $prettyprint ? JSON_PRETTY_PRINT : 0);
	}

	/**
	 * @param string $json
	 *
	 * @return $this
	 */
	public function __fromJSON(string $json){
		return $this->__fromIterable(json_decode($json, true));
	}

}
