<?php
/**
 * Interface EnumerableInterface
 *
 * @filesource   EnumerableInterface.php
 * @created      13.01.2018
 * @package      chillerlan\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits;

interface EnumerableInterface{

	/**
	 * @return array
	 */
	public function __toArray():array;

	/**
	 * @param callable $callback
	 *
	 * @return mixed
	 */
	public function __each(callable $callback);

	/**
	 * @param callable $callback
	 *
	 * @return array
	 */
	public function __map($callback):array;

	/**
	 * @return mixed
	 */
	public function __reverse();

	/**
	 * @return mixed
	 */
	public function __last();

	/**
	 * @return mixed
	 */
	public function __clear();

	/**
	 * @return string
	 */
	public function __inspect():string;

	/**
	 * @param callable $callback
	 *
	 * @return array
	 */
	public function __findAll(callable $callback):array;

	/**
	 * @param callable $callback
	 *
	 * @return array
	 */
	public function __reject(callable $callback):array;

	/**
	 * @param array $y
	 *
	 * @return bool
	 */
	public function __equal(array $y):bool;

}
