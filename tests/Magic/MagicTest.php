<?php
/**
 * Class MagicTest
 *
 * @filesource   MagicTest.php
 * @created      22.11.2017
 * @package      chillerlan\TraitTest\Magic
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\TraitTest\Magic;

use PHPUnit\Framework\TestCase;

class MagicTest extends TestCase{

	public function testMagic(){
		$magic = new MagicContainer;

		$magic->test = 'foo';

		$this->assertSame('Value: foobar', $magic->test);
		$this->assertnull($magic->foo);
	}
}
