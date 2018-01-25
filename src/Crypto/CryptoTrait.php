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
	 * @param string      $secret
	 * @param string      $public
	 * @param string|null $nonce
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	protected function createBox(string $message, string $secret, string $public, string $nonce = null):CryptoBoxInterface{
		return (new Box(['keypair' => new BoxKeypair(['secret' => $secret, 'public' => $public])]))
			->create($message, $nonce);
	}

	/**
	 * @param string $box
	 * @param string $secret
	 * @param string $public
	 * @param string $nonce
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	protected function openBox(string $box, string $secret, string $public, string $nonce):CryptoBoxInterface{
		return (new Box(['keypair' => new BoxKeypair(['secret' => $secret, 'public' => $public])]))
			->open($box, $nonce);
	}

	/**
	 * @param string      $message
	 * @param string      $secret
	 * @param string|null $nonce
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	protected function createSecretBox(string $message, string $secret, string $nonce = null):CryptoBoxInterface{
		return (new SecretBox(['keypair' => new BoxKeypair(['secret' => $secret])]))
			->create($message, $nonce);
	}

	/**
	 * @param string $box
	 * @param string $secret
	 * @param string $nonce
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	protected function openSecretBox(string $box, string $secret, string $nonce):CryptoBoxInterface{
		return (new SecretBox(['keypair' => new BoxKeypair(['secret' => $secret])]))
			->open($box, $nonce);
	}

	/**
	 * @param string $message
	 * @param string $public
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	protected function createSealedBox(string $message, string $public):CryptoBoxInterface{
		return (new SealedBox(['keypair' => new BoxKeypair(['public' => $public])]))
			->create($message);
	}

	/**
	 * @param string $box
	 * @param string $secret
	 * @param string $public
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	protected function openSealedBox(string $box, string $secret, string $public):CryptoBoxInterface{
		return (new SealedBox(['keypair' => new BoxKeypair(['secret' => $secret, 'public' => $public])]))
			->open($box);
	}

	/**
	 * @param string $message
	 * @param string $secret
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	protected function signMessage(string $message, string $secret):CryptoBoxInterface{
		return (new SignedMessage(['keypair' => new SignKeypair(['secret' => $secret])]))
			->create($message);
	}

	/**
	 * @param string $box
	 * @param string $public
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	protected function verifySignedMessage(string $box, string $public):CryptoBoxInterface{
		return (new SignedMessage(['keypair' => new SignKeypair(['public' => $public])]))
			->open($box);
	}

}
