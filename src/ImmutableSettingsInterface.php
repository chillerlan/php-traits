<?php
/**
 *
 * @filesource   ImmutableSettingsInterface.php
 * @created      04.01.2018
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits;

/**
 * a generic container with magic getter and setter
 */
interface ImmutableSettingsInterface{

	/**
	 * @param iterable|null $properties
	 */
	public function __construct(iterable $properties = null);

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
	public function __set(string $property, $value):void;

	/**
	 * @param string $property
	 *
	 * @return bool
	 */
	public function __isset(string $property):bool;

	/**
	 * @param string $property
	 *
	 * @return void
	 */
	public function __unset(string $property):void;

	/**
	 * @return string
	 */
	public function __toString():string;

	/**
	 * @return array
	 */
	public function __toArray():array;

	/**
	 * @param iterable $properties
	 *
	 * @return \chillerlan\Traits\ImmutableSettingsInterface
	 */
	public function __fromIterable(iterable $properties);

	/**
	 * @param bool|null $prettyprint
	 *
	 * @return string
	 */
	public function __toJSON(bool $prettyprint = null):string;

	/**
	 * @param string $json
	 *
	 * @return \chillerlan\Traits\ImmutableSettingsInterface
	 */
	public function __fromJSON(string $json);

}
