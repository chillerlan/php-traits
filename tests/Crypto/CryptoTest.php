<?php
/**
 * Class CryptoTest
 *
 * @filesource   CryptoTest.php
 * @created      24.01.2018
 * @package      chillerlan\TraitTest\Crypto
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\TraitTest\Crypto;

use chillerlan\Traits\Crypto\CryptoKeyInterface;
use chillerlan\Traits\Crypto\CryptoTrait;
use PHPUnit\Framework\TestCase;

class CryptoTest extends TestCase{
	use CryptoTrait;

	public function setUp(){
		if(!extension_loaded('sodium') || !function_exists('sodium_memzero')){
			$this->markTestSkipped('sodium extension (PHP 7.2+) required!');
		}

	}

	const TESTKEY                         = "\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0a\x0b\x0c\x0d\x0e\x0f\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f";
	const TESTKEY_BOX_SECRET_FROM_SEED    = '3d94eea49c580aef816935762be049559d6d1440dede12e6a125f1841fff8e6f';
	const TESTKEY_BOX_PUBLIC_FROM_SEED    = '4701d08488451f545a409fb58ae3e58581ca40ac3f7f114698cd71deac73ca01';
	const TESTKEY_BOX_PUBLIC_FROM_SECRET  = '8f40c5adb68f25624ae5b214ea767a6ec94d829d3d7b5e1ad1ba6f3e2138285f';
	const TESTKEY_SIGN_SECRET_FROM_SEED   = '000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f03a107bff3ce10be1d70dd18e74bc09967e4d6309ba50d5f1ddc8664125531b8';
	const TESTKEY_SIGN_PUBLIC_FROM_SEED   = '03a107bff3ce10be1d70dd18e74bc09967e4d6309ba50d5f1ddc8664125531b8';
	const TESTMESSAGE = 'likes are now florps';

	public function testCreateBoxKeypair(){
		$keypair = $this->createBoxKeypair();

		$this->assertInstanceOf(CryptoKeyInterface::class, $keypair);
		$this->assertSame($keypair->keypair, $keypair->secret.$keypair->public);
		$this->assertSame(SODIUM_CRYPTO_BOX_KEYPAIRBYTES, strlen($keypair->keypair));
		$this->assertSame(SODIUM_CRYPTO_BOX_SECRETKEYBYTES, strlen($keypair->secret));
		$this->assertSame(SODIUM_CRYPTO_BOX_PUBLICKEYBYTES, strlen($keypair->public));

		unset($keypair); // trigger destructor
	}

	public function testCreateBoxKeypairFromSeed(){
		$keypair = $this->createBoxKeypair($this::TESTKEY);

		$this->assertSame(sodium_hex2bin($this::TESTKEY_BOX_SECRET_FROM_SEED), $keypair->secret);
		$this->assertSame(sodium_hex2bin($this::TESTKEY_BOX_PUBLIC_FROM_SEED), $keypair->public);
	}

	/**
	 * @expectedException \chillerlan\Traits\Crypto\CryptoException
	 * @expectedExceptionMessage invalid seed length
	 */
	public function testCreateBoxKeypairInvalidSeed(){
		$this->createBoxKeypair('00');
	}

	public function testCreateBoxPublicFromSecret(){
		$keypair = $this->createBoxKeypairFromSecret($this::TESTKEY);

		$this->assertSame($this::TESTKEY, $keypair->secret);
		$this->assertSame(sodium_hex2bin($this::TESTKEY_BOX_PUBLIC_FROM_SECRET), $keypair->public);
	}

	/**
	 * @expectedException \chillerlan\Traits\TraitException
	 * @expectedExceptionMessage invalid secret key length
	 */
	public function testCreateBoxPublicFromSecretInvalidLength(){
		$this->createBoxKeypairFromSecret('00');
	}

	public function testCreateSignKeypair(){
		$keypair = $this->createSignKeypair();

		$this->assertInstanceOf(CryptoKeyInterface::class, $keypair);
		$this->assertSame($keypair->keypair, $keypair->secret.$keypair->public);
		$this->assertSame(SODIUM_CRYPTO_SIGN_SECRETKEYBYTES, strlen($keypair->secret));
		$this->assertSame(SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES, strlen($keypair->public));

		unset($keypair);
	}

	public function testCreateSignKeypairFromSeed(){
		$keypair = $this->createSignKeypair($this::TESTKEY);

		$this->assertSame(sodium_hex2bin($this::TESTKEY_SIGN_SECRET_FROM_SEED), $keypair->secret);
		$this->assertSame(sodium_hex2bin($this::TESTKEY_SIGN_PUBLIC_FROM_SEED), $keypair->public);
	}

	/**
	 * @expectedException \chillerlan\Traits\Crypto\CryptoException
	 * @expectedExceptionMessage invalid seed length
	 */
	public function testCreateSignKeypairInvalidSeed(){
		$this->createSignKeypair('00');
	}

	public function testCreateBox(){
		$kp = $this->createBoxKeypair();

		$e = $this->createBox(self::TESTMESSAGE, $kp->secret, $kp->public);
		$d = $this->openBox($e->box, $kp->secret, $kp->public, $e->nonce);

		$this->assertSame(self::TESTMESSAGE, $d->message);
	}

	public function testCreateSecretBox(){
		$kp = $this->createBoxKeypair();

		$e = $this->createSecretBox(self::TESTMESSAGE, $kp->secret);
		$d = $this->openSecretBox($e->box, $kp->secret, $e->nonce);

		$this->assertSame(self::TESTMESSAGE, $d->message);
	}

	public function testCreateSealedBox(){
		$kp = $this->createBoxKeypair();

		$e = $this->createSealedBox(self::TESTMESSAGE, $kp->public);
		$d = $this->openSealedBox($e->box, $kp->secret, $kp->public);

		$this->assertSame(self::TESTMESSAGE, $d->message);
	}

	public function testSignMessage(){
		$kp = $this->createSignKeypair();

		$e = $this->signMessage(self::TESTMESSAGE, $kp->secret);
		$d = $this->verifySignedMessage($e->box, $kp->public);

		$this->assertSame(self::TESTMESSAGE, $d->message);
	}

}
