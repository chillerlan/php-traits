<?php
/**
 * Class TestContainer
 *
 * @filesource   TestContainer.php
 * @created      22.11.2017
 * @package      chillerlan\TraitTest\Container
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\TraitTest\Container;

use chillerlan\Traits\Container;
use chillerlan\Traits\ContainerInterface;

/**
 * @property $test1
 * @property $test2
 * @property $test3
 */
class TestContainer implements ContainerInterface{
	use Container;

	protected $test1 = 'foo';

	protected $test2;

	private  $test3 = 'what';
}
