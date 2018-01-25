<?php
/**
 * Class CryptoKeypair
 *
 * @filesource   CryptoKeypair.php
 * @created      24.01.2018
 * @package      chillerlan\Traits\Crypto
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\Crypto;

use chillerlan\Traits\{Container, ContainerInterface};

/**
 * @link https://paragonie.com/book/pecl-libsodium/read/00-intro.md
 * @link https://paragonie.com/book/pecl-libsodium/read/01-quick-start.md
 */
abstract class CryptoKeypair implements CryptoKeyInterface, ContainerInterface{
	use MemzeroDestructorTrait, Container{
		__construct as containerConstruct;
	}

	/**
	 * @var string
	 */
	protected $keypair;

	/**
	 * @var string
	 */
	protected $secret;

	/**
	 * @var string
	 */
	protected $public;

	/** @noinspection PhpMissingParentConstructorInspection */
	/**
	 * CryptoKeypair constructor.
	 *
	 * @param array|null $properties
	 *
	 * @throws \chillerlan\Traits\Crypto\CryptoException
	 */
	public function __construct(array $properties = null){

		if(!extension_loaded('sodium') || !function_exists('sodium_memzero')){
			throw new CryptoException('sodium extension (PHP 7.2+) required!'); // @codeCoverageIgnore
		}

		$this->containerConstruct($properties);
	}

}
