<?php
/**
 * Trait Enumerable
 *
 * @filesource   Enumerable.php
 * @created      28.06.2017
 * @package      chillerlan\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits;

/**
 * @link http://api.prototypejs.org/language/Enumerable/
 */
trait Enumerable{

	/**
	 * @var array
	 */
	protected $array = [];

	/**
	 * @var int
	 */
	protected $offset = 0;

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/toArray/
	 *
	 * @return array
	 *
	 * @codeCoverageIgnore
	 */
	public function __toArray():array {
		return $this->array;
	}

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/each/
	 *
	 * @param callable $callback
	 *
	 * @return $this
	 */
	public function __each(callable $callback){
		$this->__map($callback);

		return $this;
	}

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/collect/
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/map/
	 *
	 * @param callable $callback
	 *
	 * @return array
	 * @throws \chillerlan\Traits\TraitException
	 */
	public function __map($callback):array {

		if(!is_callable($callback)){
			throw new TraitException('invalid callback');
		}

		$return = [];

		foreach($this->array as $index => $element){
			$return[$index] = call_user_func_array($callback, [$element, $index]);
		}

		return $return;
	}

	/**
	 * @link http://api.prototypejs.org/language/Array/prototype/reverse/
	 *
	 * @return $this
	 */
	public function __reverse(){
		$this->array  = array_reverse($this->array);
		$this->offset = 0;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function __last(){
		return $this->array[count($this->array) - 1];
	}

	/**
	 * @return $this
	 */
	public function __clear(){
		$this->array = [];

		return $this;
	}

	/**
	 * @link http://api.prototypejs.org/language/Array/prototype/inspect/
	 *
	 * @return string
	 */
	public function __inspect():string {
		return print_r($this->array, true);
	}

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/findAll/
	 *
	 * @param callable $callback
	 *
	 * @return array
	 * @throws \chillerlan\Traits\TraitException
	 */
	public function __findAll(callable $callback):array{

		if(!is_callable($callback)){
			throw new TraitException('invalid callback');
		}

		$return = [];

		foreach($this->array as $index => $element){

			if(call_user_func_array($callback, [$element, $index]) === true){
				$return[] = $element;
			}

		}

		return $return;
	}

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/reject/
	 *
	 * @param callable $callback
	 *
	 * @return array
	 * @throws \chillerlan\Traits\TraitException
	 */
	public function __reject(callable $callback):array{

		if(!is_callable($callback)){
			throw new TraitException('invalid callback');
		}

		$return = [];

		foreach($this->array as $index => $element){

			if(call_user_func_array($callback, [$element, $index]) !== true){
				$return[] = $element;
			}

		}

		return $return;
	}

	/**
	 * @param array $y
	 *
	 * @return bool
	 */
	public function __equal(array $y):bool{

		if(count($this->array) !== count($y)){
			return false;
		}

		$diff = 0;

		foreach($this->array as $kx => $vx){
			$diff |= $vx ^ $y[$kx];
		}

		return ((($diff - 1) >> 31) & 1) === 1;
	}

}
