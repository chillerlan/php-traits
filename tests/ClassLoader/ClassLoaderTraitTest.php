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
use chillerlan\Traits\TraitException;
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

	public function testLoadClassExceptionA1(){
		$this->expectException(TraitException::class);
		$this->expectExceptionMessage('\whatever\foo does not exist');

		$this->loadClass('\\whatever\\foo', tInterface::class);
	}

	public function testLoadClassExceptionA2(){
		$this->expectException(TraitException::class);
		$this->expectExceptionMessage('\whatever\bar does not exist');

		$this->loadClass(tClass::class, '\\whatever\\bar');
	}

	public function testLoadClassExceptionB(){
		$this->expectException(TraitException::class);
		$this->expectExceptionMessage('cannot be an instance of trait');

		$this->loadClass(tClass::class, tTrait::class);
	}

	public function testLoadClassExceptionC(){
		$this->expectException(TraitException::class);
		$this->expectExceptionMessage('cannot instance abstract class');

		$this->loadClass(tAbstract::class, tInterface::class);
	}

	public function testLoadClassExceptionD(){
		$this->expectException(TraitException::class);
		$this->expectExceptionMessage('cannot instance trait');

		$this->loadClass(tTrait::class, tInterface::class);
	}

	public function testLoadClassExceptionE1(){
		$this->expectException(TraitException::class);
		$this->expectExceptionMessage('does not implement');

		$this->loadClass(tClass::class, tInterface2::class);
	}

	public function testLoadClassExceptionE2(){
		$this->expectException(TraitException::class);
		$this->expectExceptionMessage('does not inherit');

		$this->loadClass(tClass::class, \stdClass::class);
	}

}
