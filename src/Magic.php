<?php
/**
 * Trait Magic
 *
 * @filesource   Magic.php
 * @created      13.11.2017
 * @package      chillerlan\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits;

/**
 * A Container that turns methods into magic properties
 */
trait Magic{

	/**
	 * @param string $name
	 *
	 * @return mixed|null
	 */
	public function __get(string $name) {
		return $this->get($name);
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return void
	 */
	public function __set(string $name, $value) {
		$this->set($name, $value);
	}

	/**
	 * @param string $name
	 *
	 * @return mixed|null
	 */
	private function get(string $name) {
		$method = 'magic_get_'.$name;

		return method_exists($this, $method) ? $this->$method() : null;
	}

	/**
	 * @param string $name
	 * @param        $value
	 *
	 * @return void
	 */
	private function set(string $name, $value) {
		$method = 'magic_set_'.$name;

		if(method_exists($this, $method)){
			$this->$method($value);
		}

	}

}
