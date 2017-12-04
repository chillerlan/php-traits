<?php
/**
 * Trait SeekableIteratorTrait
 *
 * @filesource   SeekableIteratorTrait.php
 * @created      04.12.2017
 * @package      chillerlan\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\SPL;

use chillerlan\Traits\Interfaces\IteratorTrait;
use OutOfBoundsException;

/**
 * @extends \Iterator
 * @implements \SeekableIterator
 *
 * @link http://php.net/manual/class.seekableiterator.php
 */
trait SeekableIteratorTrait{
	use IteratorTrait;

	/**
	 * @link  http://php.net/manual/seekableiterator.seek.php
	 * @inheritdoc
	 */
	public function seek($pos){
		$this->rewind();

		for( ; $this->offset < $pos; ){

			if(!next($this->array)) {
				throw new OutOfBoundsException('invalid seek position: '.$pos);
			}

			$this->offset++;
		}

	}

}
