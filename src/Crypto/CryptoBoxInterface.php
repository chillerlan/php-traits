<?php
/**
 * Interface CryptoBoxInterface
 *
 * @filesource   CryptoBoxInterface.php
 * @created      24.01.2018
 * @package      chillerlan\Traits\Crypto
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\Crypto;

/**
 * @property \chillerlan\Traits\Crypto\CryptoKeyInterface $keypair
 * @property string $box
 * @property string $nonce
 * @property string $message
 *
 * @method \chillerlan\Traits\Crypto\CryptoBoxInterface open(string $box_bin, string $nonce_bin)
 */
interface CryptoBoxInterface{

	/**
	 * @param string $message
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoBoxInterface
	 */
	public function create(string &$message):CryptoBoxInterface;

}
