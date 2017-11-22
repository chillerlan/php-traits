<?php
/**
 * Class MagicContainer
 *
 * @filesource   MagicContainer.php
 * @created      22.11.2017
 * @package      chillerlan\TraitTest\Magic
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\TraitTest\Magic;

use chillerlan\Traits\Magic;

/**
 * @property $test
 */
class MagicContainer{
	use Magic;

	protected $foo;

	protected function magic_get_test(){
		return 'Value: '.$this->foo;
	}

	protected function magic_set_test($value){
		$this->foo = $value.'bar';
	}

}
