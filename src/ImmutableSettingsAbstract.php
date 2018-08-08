<?php
/**
 * Class ImmutableSettingsAbstract
 *
 * @filesource   ImmutableSettingsAbstract.php
 * @created      24.01.2018
 * @package      chillerlan\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits;

abstract class ImmutableSettingsAbstract implements ImmutableSettingsInterface{
	use ImmutableSettingsContainer;
}
