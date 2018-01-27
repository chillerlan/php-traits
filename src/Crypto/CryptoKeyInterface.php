<?php
/**
 * Interface CryptoKeyInterface
 *
 * @filesource   CryptoKeyInterface.php
 * @created      24.01.2018
 * @package      chillerlan\Traits\Crypto
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits\Crypto;

/**
 * @property string $keypair
 * @property string $secret
 * @property string $public
 */
interface CryptoKeyInterface{

	/**
	 * @param string|null $seed_bin
	 *
	 * @return \chillerlan\Traits\Crypto\CryptoKeyInterface
	 */
	public function create(string &$seed_bin = null):CryptoKeyInterface;

}
