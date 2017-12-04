<?php
/**
 * Trait CountableTrait
 *
 * @filesource   CountableTrait.php
 * @created      03.12.2017
 * @package      chillerlan\Traits\SPL
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\SPL;

/**
 * @implements \Countable
 *
 * @link http://php.net/manual/class.countable.php
 */
trait CountableTrait{

	/**
	 * @var array
	 */
	protected $array = [];

	/**
	 * @link http://php.net/manual/countable.count.php
	 * @inheritdoc
	 */
	public function count():int{
		return count($this->array);
	}

}
