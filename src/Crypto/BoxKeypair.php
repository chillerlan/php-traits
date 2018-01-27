<?php
/**
 * Class BoxKeypair
 *
 * @filesource   BoxKeypair.php
 * @created      24.01.2018
 * @package      chillerlan\Traits\Crypto
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\Crypto;

class BoxKeypair extends CryptoKeypair{

	/** @inheritdoc */
	public function create(string &$seed_bin = null):CryptoKeyInterface{

		if($seed_bin !== null && strlen($seed_bin) !== SODIUM_CRYPTO_BOX_SEEDBYTES){
			throw new CryptoException('invalid seed length');
		}

		$keypair = $seed_bin
			? sodium_crypto_box_seed_keypair($seed_bin)
			: sodium_crypto_box_keypair();

		$this->keypair = $keypair;
		$this->secret  = sodium_crypto_box_secretkey($keypair);
		$this->public  = sodium_crypto_box_publickey($keypair);

		sodium_memzero($keypair);

		if($seed_bin !== null){
			sodium_memzero($seed_bin);
		}

		return $this;
	}

	/**
	 * @param string $secret_bin
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoKeyInterface
	 * @throws \chillerlan\Traits\Crypto\CryptoException
	 */
	public function createFromSecret(string &$secret_bin):CryptoKeyInterface{

		if(strlen($secret_bin) !== SODIUM_CRYPTO_BOX_SECRETKEYBYTES){
			throw new CryptoException('invalid secret key length');
		}

		$this->secret  = $secret_bin;
		$this->public  = sodium_crypto_box_publickey_from_secretkey($this->secret);
		$this->keypair = $this->secret.$this->secret;

		sodium_memzero($secret_bin);

		return $this;
	}

}
