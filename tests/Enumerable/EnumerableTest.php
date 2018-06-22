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

	protected $array = [1, 'two', 3, 'four', 5];

	/**
	 * @var \chillerlan\TraitTest\Enumerable\EnumerableContainer
	 */
	protected $enumerable;

	protected function setUp(){
		$this->enumerable = new EnumerableContainer($this->array);
	}

	public function testMap(){

		$arr = $this->enumerable
			->__map(function($v, $k){
				return $v;
			})
		;

		$this->assertSame($arr, $this->enumerable->__toArray());
	}

	public function testReverse(){
		$arr = $this->enumerable
			->__reverse()
			->__toArray()
		;

		$this->assertSame(array_reverse($this->array), $arr);
	}

	public function testEach(){

		$this->enumerable
			->__each(function($v, $k){
				$this->assertSame($this->array[$k], $v);
			})
		;

	}

	public function testFirst(){
		$this->assertSame(1, $this->enumerable->__first());
	}

	public function testLast(){
		$this->assertSame(5, $this->enumerable->__last());
	}

	public function testClear(){

		$arr = $this->enumerable
			->__clear()
			->__toArray()
		;

		$this->assertSame([], $arr);
	}

	public function testInspect(){

		$expected =  'Array
(
    [0] => 1
    [1] => two
    [2] => 3
    [3] => four
    [4] => 5
)
';

		$expected = str_replace("\r", '', $expected); // will we ever settle on ONE line ending that is \n???

		$this->assertSame($expected, $this->enumerable->__inspect());
	}

	public function testFindAll(){
		$enum = new EnumerableContainer([1, 'two', 3, 'four', 5]);

		$arr = $enum->__findAll(function($e, $i){
			return is_string($e);
		});

		$this->assertSame(['two', 'four'], $arr);
	}

	public function testReject(){
		$enum = new EnumerableContainer([1, 'two', 3, 'four', 5]);

		$arr = $enum->__reject(function($e, $i){
			return is_string($e);
		});

		$this->assertSame([1, 3, 5], $arr);
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage invalid callback
	 */
	public function testMapInvalidCallback(){
		(new EnumerableContainer([]))->__map('foo');
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage invalid callback
	 */
	public function testFindAllInvalidCallback(){
		(new EnumerableContainer([]))->__findAll('foo');
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage invalid callback
	 */
	public function testRejectInvalidCallback(){
		(new EnumerableContainer([]))->__reject('foo');
	}

}
