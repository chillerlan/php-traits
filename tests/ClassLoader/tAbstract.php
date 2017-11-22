<?php

namespace chillerlan\TraitTest\ClassLoader;

abstract class tAbstract implements tInterface{
	use tTrait;

	protected $foo;

	public function __construct($foo = null){
		$this->foo = $foo;
	}

	public function bar(){
		return $this->foo;
	}

	public function test(string $test):string{
		return $test;
	}
}
