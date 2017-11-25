<?php
/**
 * Class EnumerableTest
 *
 * @filesource   EnumerableTest.php
 * @created      25.11.2017
 * @package      chillerlan\TraitTest\Enumerable
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\TraitTest\Enumerable;

use PHPUnit\Framework\TestCase;

class EnumerableTest extends TestCase{

	public function testEnumerable(){
		$arr = str_split('ABC');

		/** @var \chillerlan\TraitTest\Enumerable\EnumerableContainer $enum */
		$enum = new EnumerableContainer($arr);

		$enum->__each(function($v, $k) use ($arr){
			$this->assertSame($arr[$k], $v);
		});

		$arr2 = $enum->__map(function($v, $k){
			return $v;
		});

		$this->assertSame(array_values($arr), $arr2);
		$this->assertSame($arr, $enum->__toArray());

		$arr2 = $enum->__reverse()->__toArray();

		$this->assertSame(array_reverse($arr), $arr2);
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage invalid callback
	 */
	public function testMapInvalidCallback(){
		(new EnumerableContainer([]))->__map('foo');
	}
}
