<?php
/**
 * Class ContainerTraitTest
 *
 * @filesource   ContainerTraitTest.php
 * @created      22.11.2017
 * @package      chillerlan\TraitTest\Container
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\TraitTest\Container;

use PHPUnit\Framework\TestCase;

class ContainerTraitTest extends TestCase{

	public function testConstruct(){
		$container = new TestContainer([
			'test1' => 'test1',
			'test2' => 'test2',
			'test3' => 'test3',
		]);

		$this->assertSame('test1', $container->test1);
		$this->assertSame('test2', $container->test2);
		$this->assertNull($container->test3);
	}

	public function testGet(){
		$container = new TestContainer;

		$this->assertSame('foo', $container->test1);
		$this->assertNull($container->test2);
		$this->assertNull($container->test3);
		$this->assertNull($container->foo);

		// isset test
		$this->assertTrue(isset($container->test1));
		$this->assertTrue(isset($container->test2));
		$this->assertFalse(isset($container->test3));
	}

	public function testSet(){
		$container = new TestContainer;
		$container->test1 = 'bar';
		$container->test2 = 'what';
		$container->test3 = 'nope';

		$this->assertSame('bar', $container->test1);
		$this->assertSame('what', $container->test2);
		$this->assertNull($container->test3);
	}

	public function testToArray(){

		$arr = [
			'test1' => 'no',
			'test2' => true,
		];

		$container = new TestContainer($arr);

		$this->assertSame($arr, $container->__toArray());
	}
}
