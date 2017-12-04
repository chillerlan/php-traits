<?php
/**
 * Trait IteratorTrait
 *
 * @filesource   IteratorTrait.php
 * @created      03.12.2017
 * @package      chillerlan\Traits\Interfaces
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\Interfaces;

/**
 * @extends \Traversable
 * @implements \Iterator
 *
 * @link http://php.net/manual/class.traversable.php
 */
trait IteratorTrait{

	/**
	 * @var array
	 */
	protected $array = [];

	/**
	 * @var int
	 */
	protected $offset = 0;

	/**
	 * @link  http://php.net/manual/iterator.current.php
	 * @inheritdoc
	 */
	public function current(){
		return $this->array[$this->offset] ?? null;
	}

	/**
	 * @link  http://php.net/manual/iterator.next.php
	 * @inheritdoc
	 */
	public function next(){
		$this->offset++;
	}

	/**
	 * @link  http://php.net/manual/iterator.key.php
	 * @inheritdoc
	 */
	public function key(){
		return $this->offset;
	}

	/**
	 * @link  http://php.net/manual/iterator.valid.php
	 * @inheritdoc
	 */
	public function valid():bool{
		return array_key_exists($this->offset, $this->array);
	}

	/**
	 * @link  http://php.net/manual/iterator.rewind.php
	 * @inheritdoc
	 */
	public function rewind(){
		$this->offset = 0;
	}

}
