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
	private $cryptoKeyInterface;

	/**
	 * @param string|null $secret_hex
	 * @param string|null $public_hex
	 *
	 * @return $this
	 */
	protected function setBoxKeypair(string $secret_hex = null, string $public_hex = null){
		return $this->setCryptoKeyInterface(BoxKeypair::class, $secret_hex, $public_hex);
	}

	/**
	 * @param string|null $secret_hex
	 * @param string|null $public_hex
	 *
	 * @return $this
	 */
	protected function setSignKeypair(string $secret_hex = null, string $public_hex = null){
		return $this->setCryptoKeyInterface(SignKeypair::class, $secret_hex, $public_hex);
	}

	/**
	 * @param \chillerlan\Traits\Crypto\CryptoKeyInterface $keypair
	 *
	 * @return $this
	 */
	protected function setKeypair(CryptoKeyInterface $keypair){
		unset($this->cryptoKeyInterface);

		$this->cryptoKeyInterface = $keypair;

		return $this;
	}

	/**
	 * @param string      $type_fqcn
	 * @param string|null $secret_hex
	 * @param string|null $public_hex
	 *
	 * @return $this
	 */
	private function setCryptoKeyInterface(string $type_fqcn, string &$secret_hex = null, string &$public_hex = null){
		unset($this->cryptoKeyInterface);

		$this->cryptoKeyInterface = new $type_fqcn([
			'secret' => !empty($secret_hex) ? sodium_hex2bin($secret_hex) : null,
			'public' => !empty($public_hex) ? sodium_hex2bin($public_hex) : null,
		]);

		if($secret_hex !== null){
			sodium_memzero($secret_hex);
		}

		if($public_hex !== null){
			sodium_memzero($public_hex);
		}

		return $this;
	}

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
