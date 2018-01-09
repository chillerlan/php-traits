<?php
/**
 * Class EnvTestNonGlobal
 *
 * @filesource   EnvTestNonGlobal.php
 * @created      03.01.2018
 * @package      chillerlan\TraitTest\Env
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\TraitTest\Env;

use chillerlan\Traits\DotEnv;

class EnvTestNonGlobal extends EnvTest{

	protected function setUp(){
		$this->dotenv = new DotEnv(__DIR__, '.env_test', false);
	}

	public function testLoadGet(){
		$this->dotenv->load(['VAR']);

		$this->assertFalse(isset($_ENV[42])); // numerical keys shouldn't exist in globals


		$this->assertSame([], $_ENV); // we're in non-global mode
		$this->assertFalse(isset($_ENV['VAR']));

		$this->assertSame('test', $this->dotenv->get('var'));
		$this->assertSame('test', $this->dotenv->get('VAR'));

		$this->assertSame('Oh here\'s some silly &%=ä$&/"§% value', $this->dotenv->get('TEST')); // stripped comment line
		$this->assertSame('foo'.PHP_EOL.'bar'.PHP_EOL.'nope', $this->dotenv->get('MULTILINE'));

		$this->assertSame('Hello World!', $this->dotenv->get('VAR3'));
		$this->assertSame('{$VAR1} $VAR2 {VAR1}', $this->dotenv->get('VAR4')); // not resolved
	}

	public function testSetUnsetClear(){
		$this->dotenv->load();

		$this->assertFalse(isset($_ENV['TEST']));
		$this->assertTrue($this->dotenv->isset('TEST'));
		unset($this->dotenv->TEST);
		$this->assertFalse(isset($_ENV['TEST']));
		$this->assertFalse($this->dotenv->get('test'));

		// generic
		$this->dotenv->set('TESTVAR', 'some value: ${var3}');
		$this->assertSame('some value: Hello World!', $this->dotenv->get('TESTVAR'));

		// magic
		$this->dotenv->TESTVAR = 'some other value: ${var3}';
		$this->assertSame('some other value: Hello World!', $this->dotenv->TESTVAR);

		$this->dotenv->clear();

		$this->assertSame([], $_ENV);
	}

}
