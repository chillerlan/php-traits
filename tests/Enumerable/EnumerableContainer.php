<?php
/**
 * Class EnumerableContainer
 *
 * @filesource   EnumerableContainer.php
 * @created      25.11.2017
 * @package      chillerlan\TraitTest\Enumerable
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\TraitTest\Enumerable;

use chillerlan\Traits\Enumerable;
use chillerlan\Traits\EnumerableInterface;

class EnumerableContainer implements EnumerableInterface{
	use Enumerable;

	public function __construct(array $data){
		$this->array = $data;
	}

}
