<?php
/**
 * Trait ArrayAccessTrait
 *
 * @filesource   ArrayAccessTrait.php
 * @created      03.12.2017
 * @package      chillerlan\Traits\Interfaces
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\Interfaces;

/**
 * @implements ArrayAccess
 *
 * @link http://php.net/manual/class.arrayaccess.php
 */
trait ArrayAccessTrait{

	/**
	 * @var array
	 */
	protected $array = [];

	/**
	 * @var int
	 */
	protected $offset = 0;

	/**
	 * @link  http://php.net/manual/arrayaccess.offsetexists.php
	 * @inheritdoc
	 */
	public function offsetExists($offset):bool{
		return array_key_exists($offset, $this->array);
	}

	/**
	 * @link  http://php.net/manual/arrayaccess.offsetget.php
	 * @inheritdoc
	 */
	public function offsetGet($offset){
		return $this->array[$offset] ?? null;
	}

	/**
	 * @link  http://php.net/manual/arrayaccess.offsetset.php
	 * @inheritdoc
	 */
	public function offsetSet($offset, $value){

		$offset !== null
			? $this->array[$offset] = $value
			: $this->array[] = $value;
	}

	/**
	 * @link  http://php.net/manual/arrayaccess.offsetunset.php
	 * @inheritdoc
	 */
	public function offsetUnset($offset){
		unset($this->array[$offset]);
	}

}
