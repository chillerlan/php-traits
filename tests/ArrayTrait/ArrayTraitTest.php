<?php
/**
 *
 * @filesource   ArrayTraitTest.php
 * @created      04.12.2017
 * @package      chillerlan\TraitTest\ArrayTrait
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\TraitTest\ArrayTrait;

use PHPUnit\Framework\TestCase;

class SearchableArrayTest extends TestCase{

	public function testSearchableArray(){
		// https://api.guildwars2.com/v2/continents/2/floors/1
		$array = new TestArrayClass(json_decode(file_get_contents(__DIR__.'/gw2api-floors-2-1.json'), true));

		$k = 'regions.7.maps.38.points_of_interest.990.name';
		$v = 'Stonemist Keep';

		// coverage - first level
		$this->assertSame('World vs. World', $array->get('regions')[7]['name']);
		$this->assertTrue($array->in('regions'));

		$this->assertSame($v, $array->get($k));
		$this->assertSame($v, $array->searchByKey($k)); // RecursiveIterator
		$this->assertSame([$k => $v], $array->searchByValue($v)); // RecursiveIterator

		$this->assertNull($array->get($k.'.foo'));
		$this->assertNull($array->searchByKey($k.'.foo')); // RecursiveIterator

		$this->assertTrue($array->in($k));
		$this->assertTrue($array->isset($k)); // RecursiveIterator

		$this->assertFalse($array->in($k.'.foo'));
		$this->assertFalse($array->isset($k.'.foo')); // RecursiveIterator

		$array->set($k, 'foo');
		$this->assertSame('foo', $array->get($k));

	}

	public function testSet(){
		$array = new TestArrayClass;

		$this->assertFalse($array->set('', null)->in('nothing'));

		$array->set('foo.bar', 'whatever');

		$this->assertSame('whatever', $array->get('foo.bar'));
	}

}
