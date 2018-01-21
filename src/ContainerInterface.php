<?php
/**
 *
 * @filesource   ContainerInterface.php
 * @created      04.01.2018
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits;

/**
 * a generic container with magic getter and setter
 */
interface ContainerInterface{

	/**
	 * @param array $properties
	 */
	public function __construct(array $properties = null);

	/**
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function __get(string $property);

	/**
	 * @param string $property
	 * @param mixed  $value
	 *
	 * @return void
	 */
	public function __set(string $property, $value);

	/**
	 * @return array
	 */
	public function __toArray():array;
}