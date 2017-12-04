<?php
/**
 * Trait SerializableTrait
 *
 * @filesource   SerializableTrait.php
 * @created      04.12.2017
 * @package      chillerlan\Traits\Interfaces
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\Interfaces;

/**
 * @implements \Serializable
 *
 * @link http://php.net/manual/class.serializable.php
 */
trait SerializableTrait{

	/**
	 * @var array
	 */
	protected $array = [];

	/**
	 * @link  http://php.net/manual/serializable.serialize.php
	 * @inheritdoc
	 */
	public function serialize():string {
		return serialize($this->array);
	}

	/**
	 * @link  http://php.net/manual/serializable.unserialize.php
	 * @inheritdoc
	 */
	public function unserialize($serialized){
		$this->array = unserialize($serialized);
	}

}
