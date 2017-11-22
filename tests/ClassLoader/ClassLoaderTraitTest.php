<?php
/**
 * Class ClassLoaderTraitTest
 *
 * @filesource   ClassLoaderTraitTest.php
 * @created      22.11.2017
 * @package      chillerlan\TraitTest\ClassLoader
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\TraitTest\ClassLoader;

use chillerlan\Traits\ClassLoader;
use PHPUnit\Framework\TestCase;

class ClassLoaderTraitTest extends TestCase{
	use ClassLoader;

	public function testLoadClass1(){
		$this->assertInstanceOf(tClass::class, $this->loadClass(tClass::class));
		$this->assertInstanceOf(tClass::class, $this->loadClass(tClass::class, tClass::class));
		$this->assertInstanceOf(tInterface::class, $this->loadClass(tClass::class, tInterface::class));
		$this->assertInstanceOf(tAbstract::class, $this->loadClass(tClass::class, tAbstract::class));
	}

	public function testLoadClass2(){
		$obj = $this->loadClass(tClass::class, tInterface::class);
		$this->assertSame('foo', $obj->test('foo'));
		$this->assertSame('bar', $obj->testTrait('bar'));
	}

	public function testLoadClass3(){
		$obj = $this->loadClass(tClass::class, tInterface::class, 'whatever');

		$this->assertSame('whatever', $obj->bar());
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage \whatever\foo does not exist
	 */
	public function testLoadClassExceptionA1(){
		$this->loadClass('\\whatever\\foo', tInterface::class);
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage \whatever\bar does not exist
	 */
	public function testLoadClassExceptionA2(){
		$this->loadClass(tClass::class, '\\whatever\\bar');
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage cannot be an instance of trait
	 */
	public function testLoadClassExceptionB(){
		$this->loadClass(tClass::class, tTrait::class);
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage cannot instance abstract class
	 */
	public function testLoadClassExceptionC(){
		$this->loadClass(tAbstract::class, tInterface::class);
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage cannot instance trait
	 */
	public function testLoadClassExceptionD(){
		$this->loadClass(tTrait::class, tInterface::class);
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage does not implement
	 */
	public function testLoadClassExceptionE1(){
		$this->loadClass(tClass::class, tInterface2::class);
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage does not inherit
	 */
	public function testLoadClassExceptionE2(){
		$this->loadClass(tClass::class, \stdClass::class);
	}

}
