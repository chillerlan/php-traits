<?php
/**
 * Class SealedBox
 *
 * @filesource   SealedBox.php
 * @created      25.01.2018
 * @package      chillerlan\Traits\Crypto
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\Crypto;

class SealedBox extends CryptoBox{

	/** @inheritdoc */
	public function create(string &$message):CryptoBoxInterface{
		$this->checkKeypair(null, SODIUM_CRYPTO_BOX_PUBLICKEYBYTES);

		$this->box = sodium_crypto_box_seal($this->checkMessage($message), $this->keypair->public);

		sodium_memzero($message);

		return $this;
	}

	/** @inheritdoc */
	public function open(string &$box_bin):CryptoBoxInterface{
		$this->checkKeypair(SODIUM_CRYPTO_BOX_SECRETKEYBYTES, SODIUM_CRYPTO_BOX_PUBLICKEYBYTES);

		$keypair       = sodium_crypto_box_keypair_from_secretkey_and_publickey($this->keypair->secret, $this->keypair->public);
		$this->message = sodium_crypto_box_seal_open($box_bin, $keypair);

		sodium_memzero($keypair);
		sodium_memzero($box_bin);

		if($this->message === false){
			throw new CryptoException('invalid box'); // @codeCoverageIgnore
		}

		return $this;
	}

}
