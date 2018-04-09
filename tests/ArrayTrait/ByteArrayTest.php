<?php
/**
 * Class ByteArrayTest
 *
 * @filesource   ByteArrayTest.php
 * @created      09.12.2017
 * @package      chillerlan\TraitTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\TraitTest;

use chillerlan\Traits\ArrayHelpers\ByteArrayDispenser;
use PHPUnit\Framework\TestCase;

class ByteArrayTest extends TestCase{

	/**
	 * @var \chillerlan\Traits\ArrayHelpers\ByteArrayDispenser
	 */
	protected $arrayDispenser;

	protected function setUp(){
		$this->arrayDispenser = new ByteArrayDispenser;
	}

	public function testConvert(){
		$hex_input    = '000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f';

		$expected_bin  = '0000000000000001000000100000001100000100000001010000011000000111000010000000100100001010000010110000110000001101000011100000111100010000000100010001001000010011000101000001010100010110000101110001100000011001000110100001101100011100000111010001111000011111';
		$expected_b64  = 'AAECAwQFBgcICQoLDA0ODxAREhMUFRYXGBkaGxwdHh8=';
		$expected_json = '[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31]';
		$expected_arr  = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31];
		$expected_str  = hex2bin($hex_input);

		$this->assertSame($expected_bin, $this->arrayDispenser->fromHex($hex_input)->toBin());
		$this->assertSame($expected_b64, $this->arrayDispenser->fromHex($hex_input)->toBase64());
		$this->assertSame($expected_json, $this->arrayDispenser->fromHex($hex_input)->toJSON());
		$this->assertSame($expected_arr, $this->arrayDispenser->fromHex($hex_input)->toArray());
		$this->assertSame($expected_str, $this->arrayDispenser->fromHex($hex_input)->toString());

		$this->assertSame($hex_input, $this->arrayDispenser->fromBin($expected_bin)->toHex());
		$this->assertSame($hex_input, $this->arrayDispenser->fromBase64($expected_b64)->toHex());
		$this->assertSame($hex_input, $this->arrayDispenser->fromJSON($expected_json)->toHex());
		$this->assertSame($hex_input, $this->arrayDispenser->fromArray($expected_arr)->toHex());
		$this->assertSame($hex_input, $this->arrayDispenser->fromString($expected_str)->toHex());
	}

	public function testFromfromIntSize(){
		$this->assertSame([null,null,null,null], $this->arrayDispenser->fromIntSize(4)->toArray());
	}

	public function testFromArrayFill(){
		$this->assertSame([42,42,42], $this->arrayDispenser->fromArrayFill(3, 42)->toArray());
		$this->assertSame([[],[],[]], $this->arrayDispenser->fromArrayFill(3, [])->toArray());
	}

	public function guessFromData(){
		return [
			'hex'       => ['000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f'],
			'bin'       => ['0000000000000001000000100000001100000100000001010000011000000111000010000000100100001010000010110000110000001101000011100000111100010000000100010001001000010011000101000001010100010110000101110001100000011001000110100001101100011100000111010001111000011111'],
			'base64'    => ['AAECAwQFBgcICQoLDA0ODxAREhMUFRYXGBkaGxwdHh8='],
			'json'      => ['[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31]'],
			'array'     => [[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31]],
			'string'    => [hex2bin('000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f')],
			'ByteArray' => [(new ByteArrayDispenser())->fromHex('000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f')],
		];
	}

	/**
	 * @dataProvider guessFromData
	 */
	public function testGuessFrom($from){
		$this->assertSame('000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f', $this->arrayDispenser->guessFrom($from)->toHex());
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage invalid input
	 */
	public function testGuessFromInvalidData(){
		$this->arrayDispenser->guessFrom(new \stdClass);
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage invalid size
	 */
	public function testInvalidIntException(){
		$this->arrayDispenser->fromIntSize(-1);
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage invalid length
	 */
	public function testInvalidRangeException(){
		$this->arrayDispenser->fromArrayFill(-1, 1);
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage invalid hex string
	 */
	public function testInvalidHexException(){
		$this->arrayDispenser->fromHex('foo');
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage invalid JSON array
	 */
	public function testInvalidJSONException(){
		$this->arrayDispenser->fromJSON('{}');
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage invalid base64 string
	 */
	public function testInvalidBase64Exception(){
		$this->arrayDispenser->fromBase64('\\');
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage invalid binary string
	 */
	public function testInvalidBinException(){
		$this->arrayDispenser->fromBin('2');
	}

	public function testCopyFrom(){
		$h1 = '000102030405060708090a0b0c0d0e0f';
		$h2 = '101112131415161718191a1b1c1d1e1f';

		$b2 = $this->arrayDispenser->fromHex($h2);

		// note the "referenced" behaviour of \SplFixedArray
		$this->assertSame($h1.$h2, $this->arrayDispenser->fromHex($h1)->copyFrom($b2)->toHex());
		$this->assertSame($h2, $this->arrayDispenser->fromHex($h1)->copyFrom($b2, null, 0)->toHex());
		$this->assertSame($h2, $this->arrayDispenser->fromHex($h1)->copyFrom($b2, 16, 0)->toHex());
		$this->assertSame('101112131415161708090a0b0c0d0e0f', $this->arrayDispenser->fromHex($h1)->copyFrom($b2, 8, 0)->toHex());
	}

	public function testSlice(){
		$h1 = '000102030405060708090a0b0c0d0e0f';
		$h2 = '101112131415161718191a1b1c1d1e1f';

		$b = $this->arrayDispenser->fromHex($h1.$h2);

		$this->assertSame($h1, $b->slice(0, 16)->toHex());
		$this->assertSame($h2, $b->slice(16, 16)->toHex());
	}

	public function testEqual(){
		$h1 = '000102030405060708090a0b0c0d0e0f';

		$b = $this->arrayDispenser->fromHex($h1);

		$this->assertTrue($b->equal($this->arrayDispenser->fromHex($h1)));
		$this->assertTrue($b->equal($this->arrayDispenser->fromArray([0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15])));

		$this->assertFalse($b->equal($this->arrayDispenser->fromHex('000102030405060708090a0b0c0d0e0e')));
		$this->assertFalse($b->equal($this->arrayDispenser->fromHex('000102030405060708090a0b0c0d0e')));
	}

}
