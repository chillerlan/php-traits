<?php
/**
 * Trait ByteArrayDispenser
 *
 * @filesource   ByteArrayDispenser.php
 * @created      05.12.2017
 * @package      chillerlan\Traits\ArrayHelpers
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\ArrayHelpers;

use chillerlan\Traits\TraitException;
use Exception, Traversable;

/**
 *
 */
class ByteArrayDispenser{

	/**
	 * @var string
	 */
	protected $byteArrayClass = ByteArray::class;

	/**
	 * @param int $int
	 *
	 * @return bool
	 */
	public function isAllowedInt(int $int):bool{
		return $int >= 0 && $int <= PHP_INT_MAX;
	}

	/**
	 * @param int $size
	 *
	 * @return \chillerlan\Traits\ArrayHelpers\ByteArray
	 * @throws \chillerlan\Traits\TraitException
	 */
	public function fromIntSize(int $size):ByteArray{

		if(!$this->isAllowedInt($size)){
			throw new TraitException('invalid size');
		}

		return new $this->byteArrayClass($size);
	}

	/**
	 * @param array $array
	 * @param bool  $save_indexes
	 *
	 * @return \chillerlan\Traits\ArrayHelpers\ByteArray
	 * @throws \chillerlan\Traits\TraitException
	 */
	public function fromArray($array, $save_indexes = null):ByteArray{

		try{
			$out = $this->fromIntSize(count($array));

			$array = ($save_indexes ?? true) ? $array : array_values($array);

			foreach($array as $k => $v){
				$out[$k] = $v;
			}

			return $out;
		}
		// this can be anything
		// @codeCoverageIgnoreStart
		catch(Exception $e){
			throw new TraitException($e->getMessage());
		}
		// @codeCoverageIgnoreEnd

	}

	/**
	 * @param int $len
	 *
	 * @param mixed $fill
	 *
	 * @return \chillerlan\Traits\ArrayHelpers\ByteArray
	 * @throws \chillerlan\Traits\TraitException
	 */
	public function fromArrayFill(int $len, $fill = null):ByteArray{

		if(!$this->isAllowedInt($len)){
			throw new TraitException('invalid length');
		}

		return $this->fromArray(array_fill(0, $len, $fill));
	}

	/**
	 * @param string $str
	 *
	 * @return \chillerlan\Traits\ArrayHelpers\ByteArray
	 */
	public function fromString(string $str):ByteArray{
		return $this->fromArray(unpack('C*', $str), false);
	}

	/**
	 * checks if the given string is a hex string: ab12cd34 (case insensitive, whitespace allowed)
	 *
	 * @param string $hex
	 *
	 * @return bool
	 */
	public function isAllowedHex(string $hex):bool{
		return preg_match('/^[\s\r\n\t \da-f]+$/i', $hex) && strlen($hex) % 2 === 0;
	}

	/**
	 * @param string $hex
	 *
	 * @return \chillerlan\Traits\ArrayHelpers\ByteArray|mixed
	 * @throws \chillerlan\Traits\TraitException
	 */
	public function fromHex(string $hex):ByteArray{
		$hex = preg_replace('/[\s\r\n\t ]/', '', $hex);

		if(!$this->isAllowedHex($hex)){
			throw new TraitException('invalid hex string');
		}

		return $this->fromString(pack('H*', $hex));
	}

	/**
	 * checks if the given (trimmed) JSON string is a an array that contains numbers: [1, 2, 3]
	 *
	 * @param string $json
	 *
	 * @return bool
	 */
	public function isAllowedJSON(string $json):bool{
		return preg_match('/^\\[[\s\d,]+\\]$/', $json) > 0;
	}

	/**
	 * @param string $json
	 *
	 * @return \chillerlan\Traits\ArrayHelpers\ByteArray|mixed
	 * @throws \chillerlan\Traits\TraitException
	 */
	public function fromJSON(string $json):ByteArray{
		$json = trim($json);

		if(!$this->isAllowedJSON($json)){
			throw new TraitException('invalid JSON array');
		}

		return $this->fromArray(json_decode(trim($json)));
	}

	/**
	 * checks if the given (trimmed) string is base64 encoded binary
	 *
	 * @param string $base64
	 *
	 * @return bool
	 */
	public function isAllowedBase64(string $base64):bool{
		return preg_match('#^[a-z\d/]*={0,2}$#i', $base64) > 0;
	}

	/**
	 * @param string $base64
	 *
	 * @return \chillerlan\Traits\ArrayHelpers\ByteArray|mixed
	 * @throws \chillerlan\Traits\TraitException
	 */
	public function fromBase64(string $base64):ByteArray{
		$base64 = trim($base64);

		if(!$this->isAllowedBase64($base64)){
			throw new TraitException('invalid base64 string');
		}

		return $this->fromString(base64_decode($base64));
	}

	/**
	 * checks if the given (trimmed) string is a binary string: [01] in multiples of 8
	 *
	 * @param string $bin
	 *
	 * @return bool
	 */
	public function isAllowedBin(string $bin):bool{
		return preg_match('/^[01]+$/', $bin) > 0 && strlen($bin) % 8 === 0;
	}

	/**
	 * @param string $bin
	 *
	 * @return \chillerlan\Traits\ArrayHelpers\ByteArray
	 * @throws \chillerlan\Traits\TraitException
	 */
	public function fromBin(string $bin):ByteArray{
		$bin = trim($bin);

		if(!$this->isAllowedBin($bin)){
			throw new TraitException('invalid binary string');
		}

		return $this->fromArray(array_map('bindec', str_split($bin, 8)));
	}

	/**
	 * @param string|array|\SplFixedArray $data
	 *
	 * @return \chillerlan\Traits\ArrayHelpers\ByteArray
	 * @throws \chillerlan\Traits\TraitException
	 */
	public function guessFrom($data):ByteArray{

		if($data instanceof Traversable){
			return $this->fromArray(iterator_to_array($data));
		}

		if(is_array($data)){
			return $this->fromArray($data);
		}

		if(is_string($data)){

			foreach(['Bin', 'Hex', 'JSON', 'Base64'] as $type){

				if(call_user_func_array([$this, 'isAllowed'.$type], [$data]) === true){
					return call_user_func_array([$this, 'from'.$type], [$data]);
				}

			}

			return $this->fromString($data);
		}

		throw new TraitException('invalid input');
	}

}
