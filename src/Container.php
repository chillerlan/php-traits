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
	 * @var \chillerlan\Traits\DotEnv|null
	 */
	private $env;

	/**
	 * @param iterable                       $properties
	 * @param \chillerlan\Traits\DotEnv|null $env
	 */
	public function __construct(array $properties = null, DotEnv $env = null){
		$this->env = $env;

		if(!empty($properties)){
			$this->__fromIterable($properties);
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
		elseif($this->env instanceof DotEnv){
			return $this->env->get($property);
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
		}
		elseif($this->env instanceof DotEnv){
			$this->env->set($property, $value);
		}

	}

	/**
	 * @param string $property
	 *
	 * @return bool
	 */
	public function __isset(string $property):bool{
		return (property_exists($this, $property) && !$this->__isPrivate($property)) || ($this->env instanceof DotEnv && $this->env->get($property));
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
	 * @return string
	 */
	public function __toJSON():string{
		return json_encode($this->__toArray());
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
