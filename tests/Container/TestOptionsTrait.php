<?php
/**
 * Trait TestOptionsTrait
 *
 * @filesource   TestOptionsTrait.php
 * @created      21.06.2018
 * @package      chillerlan\TraitTest\Container
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\TraitTest\Container;

trait TestOptionsTrait{

	protected $test1 = 'foo';

	protected $test2;

	protected $testConstruct;

	protected function TestOptionsTrait(){
		$this->testConstruct = 'success';
	}

}
