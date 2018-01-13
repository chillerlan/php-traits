<?php
/**
 * Class ByteArray
 *
 * @filesource   ByteArray.php
 * @created      05.12.2017
 * @package      chillerlan\Traits\ArrayHelpers
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\ArrayHelpers;

use ReflectionClass, SplFixedArray;

/**
 * @extends \SplFixedArray
 */
class ByteArray extends SplFixedArray{

	/**
	 * @return string
	 */
	public function toString():string{
		return $this->map('chr');
	}

	/**
	 * @return string
	 */
	public function toHex():string{
		return $this->map(function($v){
			return str_pad(dechex($v), '2', '0', STR_PAD_LEFT);
		});
	}

	/**
	 * @return string
	 */
	public function toJSON():string{
		return json_encode($this->toArray());
	}

	/**
	 * @return string
	 */
	public function toBase64():string{
		return base64_encode($this->toString());
	}

	/**
	 * @return string
	 */
	public function toBin():string{
		return $this->map(function($v){
			return str_pad(decbin($v), '8', '0', STR_PAD_LEFT);
		});
	}

	/**
	 * @param callable $m
	 *
	 * @return string
	 */
	public function map(callable $m):string{
		return implode('', array_map($m, $this->toArray()));
	}

	/**
	 * @param \SplFixedArray $src
	 * @param int            $length
	 * @param int|null       $offset
	 * @param int|null       $srcOffset
	 *
	 * @return \chillerlan\Traits\ArrayHelpers\ByteArray
	 */
	public function copyFrom(SplFixedArray $src, int $length = null, int $offset = null, int $srcOffset = null):ByteArray{
		$length    = $length ?? $src->count();
		$offset    = $offset ?? $length;
		$srcOffset = $srcOffset ?? 0;

		$diff = $offset + $length;

		if($diff > $this->count()){
			$this->setSize($diff);
		}

		for($i = 0; $i < $length; $i++){
			$this[$i + $offset] = $src[$i + $srcOffset];
		}

		return $this;
	}

	/**
	 * @param int      $offset
	 * @param int|null $length
	 *
	 * @return \chillerlan\Traits\ArrayHelpers\ByteArray
	 */
	public function slice(int $offset, int $length = null):ByteArray{

		// keep an extended class
		/** @var \chillerlan\Traits\ArrayHelpers\ByteArray $slice */
		$slice  = (new ReflectionClass($this))->newInstanceArgs([$length ?? ($this->count() - $offset)]);

		foreach($slice as $i => $_){
			$slice[$i] = $this[$offset + $i];
		}

		return $slice;
	}

	/**
	 * @param \SplFixedArray $array
	 *
	 * @return bool
	 */
	public function equal(SplFixedArray $array):bool{

		if($this->count() !== $array->count()){
			return false;
		}

		$diff = 0;

		foreach($this as $k => $v){
			$diff |= $v ^ $array[$k];
		}

		$diff = ($diff - 1) >> 31;

		return ($diff & 1) === 1;
	}

}
