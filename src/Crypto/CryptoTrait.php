<?php
/**
 * Trait CryptoTrait
 *
 * @filesource   CryptoTrait.php
 * @created      24.01.2018
 * @package      chillerlan\Traits\Crypto
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\Crypto;

trait CryptoTrait{

	/**
	 * @var \chillerlan\Traits\Crypto\CryptoKeyInterface
	 */
	protected $cryptoKeyInterface;

	/**
	 * @param string $seed_bin
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoKeyInterface
	 */
	protected function createBoxKeypair(string $seed_bin = null):CryptoKeyInterface{
		return (new BoxKeypair)->create($seed_bin);
	}

	/**
	 * @param string|null $secret_bin
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoKeyInterface
	 */
	protected function createBoxKeypairFromSecret(string $secret_bin = null):CryptoKeyInterface{
		return (new BoxKeypair)->createFromSecret($secret_bin);
	}

	/**
	 * @param string $seed_bin
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoKeyInterface
	 */
	protected function createSignKeypair(string $seed_bin = null):CryptoKeyInterface{
		return (new SignKeypair)->create($seed_bin);
	}

	/**
	 * @param string      $message
	 * @param string|null $nonce
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	protected function createBox(string $message, string $nonce = null):CryptoBoxInterface{
		return (new Box(['keypair' => $this->cryptoKeyInterface]))->create($message, $nonce);
	}

	/**
	 * @param string $box
	 * @param string $nonce
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	protected function openBox(string $box, string $nonce):CryptoBoxInterface{
		return (new Box(['keypair' => $this->cryptoKeyInterface]))->open($box, $nonce);
	}

	/**
	 * @param string      $message
	 * @param string|null $nonce
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	protected function createSecretBox(string $message, string $nonce = null):CryptoBoxInterface{
		return (new SecretBox(['keypair' => $this->cryptoKeyInterface]))->create($message, $nonce);
	}

	/**
	 * @param string $box
	 * @param string $nonce
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	protected function openSecretBox(string $box, string $nonce):CryptoBoxInterface{
		return (new SecretBox(['keypair' => $this->cryptoKeyInterface]))->open($box, $nonce);
	}

	/**
	 * @param string $message
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	protected function createSealedBox(string $message):CryptoBoxInterface{
		return (new SealedBox(['keypair' => $this->cryptoKeyInterface]))->create($message);
	}

	/**
	 * @param string $box
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	protected function openSealedBox(string $box):CryptoBoxInterface{
		return (new SealedBox(['keypair' => $this->cryptoKeyInterface]))->open($box);
	}

	/**
	 * @param string $message
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	protected function signMessage(string $message):CryptoBoxInterface{
		return (new SignedMessage(['keypair' => $this->cryptoKeyInterface]))->create($message);
	}

	/**
	 * @param string $box
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	protected function verifySignedMessage(string $box):CryptoBoxInterface{
		return (new SignedMessage(['keypair' => $this->cryptoKeyInterface]))->open($box);
	}

}
