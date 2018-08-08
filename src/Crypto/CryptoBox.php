<?php
/**
 * Class CryptoBox
 *
 * @filesource   CryptoBox.php
 * @created      25.01.2018
 * @package      chillerlan\Traits\Crypto
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\Crypto;

use chillerlan\Traits\{
	ImmutableSettingsContainer, ImmutableSettingsInterface
};

/**
 * @link https://paragonie.com/book/pecl-libsodium/read/00-intro.md
 * @link https://paragonie.com/book/pecl-libsodium/read/01-quick-start.md
 */
abstract class CryptoBox implements CryptoBoxInterface, ImmutableSettingsInterface{
	use MemzeroDestructorTrait, ImmutableSettingsContainer{
		__construct as containerConstruct;
	}

	/**
	 * @var \chillerlan\Traits\Crypto\CryptoKeyInterface
	 */
	protected $keypair;

	/**
	 * @var string
	 */
	protected $box;

	/**
	 * @var string
	 */
	protected $nonce;

	/**
	 * @var string
	 */
	protected $message;

	/** @noinspection PhpMissingParentConstructorInspection */
	/**
	 * CryptoBox constructor.
	 *
	 * @param iterable|null $properties
	 *
	 * @throws \chillerlan\Traits\Crypto\CryptoException
	 */
	public function __construct(iterable $properties = null){

		if(!extension_loaded('sodium') || !function_exists('sodium_memzero')){
			throw new CryptoException('sodium extension (PHP 7.2+) required!'); // @codeCoverageIgnore
		}

		$this->containerConstruct($properties);
	}

	/**
	 * @param int $secretLength
	 * @param int $PublicLength
	 *
	 * @return void
	 * @throws \chillerlan\Traits\Crypto\CryptoException
	 */
	protected function checkKeypair(int $secretLength = null, int $PublicLength = null):void{

		if($secretLength !== null){
			if(!$this->keypair->secret || strlen($this->keypair->secret) !== $secretLength){
				throw new CryptoException('invalid secret key');
			}
		}

		if($PublicLength !== null){
			if(!$this->keypair->public || strlen($this->keypair->public) !== $PublicLength){
				throw new CryptoException('invalid public key');
			}
		}

	}

	/**
	 * @param string $message
	 *
	 * @return string
	 * @throws \chillerlan\Traits\Crypto\CryptoException
	 */
	protected function checkMessage(string $message):string{
		$message = trim($message);

		if(empty($message)){
			throw new CryptoException('invalid message');
		}

		// @todo: padding?
		return $message;
	}

}
