<?php
/**
 * Class ArrayAccessTest
 *
 * @filesource   ArrayAccessTest.php
 * @created      03.12.2017
 * @package      chillerlan\TraitTest\Interfaces
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\TraitTest\Interfaces;

use PHPUnit\Framework\TestCase;

class ArrayAccessTest extends TestCase{

	public function testInstance(){
		$x = new TestArrayAccessContainer;

		foreach(range(0, 68) as $k){
			$x[$k] = ['id' => $k, 'hash' => md5($k)];
		}

		$x[] = ['id' => 69, 'hash' => md5(69)]; // coverage

		$this->assertCount(70, $x); // \Countable

		$x->seek(69);

		$this->assertSame(md5(69), $x->current()['hash']);

		foreach($x as $k => $v){ // \Iterator
			if(isset($x[$k])){ // coverage
				$this->assertSame(md5($k), $v['hash']);
				$this->assertSame($v, $x[$k]); // coverage
				unset($x[$k]); // coverage
			}
		}

		$this->assertCount(0, $x);
	}

	/**
	 * @expectedException \OutOfBoundsException
	 * @expectedExceptionMessage invalid seek position: 69
	 */
	public function testSeekInvalidPos(){
		$x = new TestArrayAccessContainer;
		$x->seek(69);
	}
}
