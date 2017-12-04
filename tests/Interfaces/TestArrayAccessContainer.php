<?php
/**
 * Class TestArrayAccessContainer
 *
 * @filesource   TestArrayAccessContainer.php
 * @created      03.12.2017
 * @package      chillerlan\TraitTest\Interfaces
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\TraitTest\Interfaces;

use chillerlan\Traits\Interfaces\ArrayAccessTrait;
use chillerlan\Traits\SPL\CountableTrait;
use chillerlan\Traits\SPL\SeekableIteratorTrait;

class TestArrayAccessContainer implements \ArrayAccess, \Countable, \SeekableIterator{
	use ArrayAccessTrait, CountableTrait, SeekableIteratorTrait;
}
