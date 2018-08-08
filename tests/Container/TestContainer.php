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

use chillerlan\Traits\ImmutableSettingsAbstract;

/**
 * @property $test1
 * @property $test2
 * @property $test3
 * @property $testConstruct
 */
class TestContainer extends ImmutableSettingsAbstract{
	use TestOptionsTrait;

	private  $test3 = 'what';
}
