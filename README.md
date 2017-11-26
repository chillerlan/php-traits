# php-traits

A collection of (more or less) useful traits for PHP7+

- `ClassLoader` - invokes objects of a given class and interface/type with an arbitrary count of constructor arguments
- `Container` - provides a magic getter & setter as well as a `__toArray()` method
- `Magic` - turns methods into magic properties
- `Enumerable` - provides some of [prototype's enumerable methods](http://api.prototypejs.org/language/Enumerable/)
- `Env` - loads contents from a `.env` file into the environment (similar to [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv))

[![version][packagist-badge]][packagist]
[![license][license-badge]][license]
[![Travis][travis-badge]][travis]
[![Coverage][coverage-badge]][coverage]
[![Scrunitizer][scrutinizer-badge]][scrutinizer]
[![Code Climate][codeclimate-badge]][codeclimate]

[packagist-badge]: https://img.shields.io/packagist/v/chillerlan/php-traits.svg
[packagist]: https://packagist.org/packages/chillerlan/php-traits
[license-badge]: https://img.shields.io/packagist/l/chillerlan/php-traits.svg
[license]: https://github.com/codemasher/php-traits/blob/master/LICENSE
[travis-badge]: https://img.shields.io/travis/codemasher/php-traits.svg
[travis]: https://travis-ci.org/codemasher/php-traits
[coverage-badge]: https://img.shields.io/codecov/c/github/codemasher/php-traits.svg
[coverage]: https://codecov.io/github/codemasher/php-traits
[scrutinizer-badge]: https://img.shields.io/scrutinizer/g/codemasher/php-traits.svg
[scrutinizer]: https://scrutinizer-ci.com/g/codemasher/php-traits
[codeclimate-badge]: https://img.shields.io/codeclimate/github/codemasher/php-traits.svg
[codeclimate]: https://codeclimate.com/github/codemasher/php-traits

## Documentation

### Installation
**requires [composer](https://getcomposer.org)**

*composer.json* (note: replace `dev-master` with a version boundary)
```json
{
	"require": {
		"php": ">=7.0.3",
		"chillerlan/php-traits": "dev-master"
	}
}
```

#### Manual installation
Download the desired version of the package from [master](https://github.com/codemasher/php-traits/archive/master.zip) or 
[release](https://github.com/codemasher/php-traits/releases) and extract the contents to your project folder.  After that:
- run `composer install` to install the required dependencies and generate `/vendor/autoload.php`.
- if you use a custom autoloader, point the namespace `chillerlan\Traits` to the folder `src` of the package 

Profit!

### Usage

#### `ClassLoader`
Simple usage:
```php
class MyClass{
	use ClassLoader;
	
	protected function doStuff(string $class){
		$obj = $this->loadClass(__NAMESPACE__.'\\Whatever\\'.$class);
		
		// do stuff
	}
}
```

Let's assume you have several classes that implement the same interface, but their constructors have different parameter counts, like so:
```php
class SomeClass implements MyInterface{
	public funtion __construct($param_foo){}
}

class OtherClass implements MyInterface{
	public funtion __construct($param_foo, $param_bar){}
}
```

Initialize an object based on a selction

```php
class MyClass{
	use ClassLoader;
	
	protected $classes = [
		'foo' => SomeClass::class, 
		'bar' => OtherClass::class
	];
	
	protected funtion initInterface(string $whatever, $foo, $bar = null):MyInterface{
	
		foreach($this->classes as $what => $class){
			if($whatever === $what){
				return $this->loadClass($class, MyInterface::class, $foo, $bar);
			}
		}
	
	}
}
```


#### `Container`
```php
class MyContainer{
	use Container;

	protected $foo;
	protected $bar;
}
```

```php
// use it just like a \stdClass
$container = new MyContainer;
$container->foo = 'what';
$container->bar = 'foo';

// which is equivalent to 
$container = new MyContainer(['bar' => 'foo', 'foo' => 'what']);

// fetch all properties as array
$container->__toArray(); // -> ['foo' => 'what', 'bar' => 'foo']

//non-existing properties will be ignored:
$container->nope = 'what';

var_dump($container->nope); // -> null
```


#### `Magic`
`Magic` works basically the same way as `Container`, except it accesses internal methods instead of properties.
```php
class MyMagicContainer{
	use Magic;

	protected $foo;

	protected function magic_get_foo(){
		// do whatever...
		
		return 'foo: '.$this->foo;
	}

	protected function magic_set_foo($value){
		// do stuff with $value
		// ...
		
		$this->foo = $value.'bar';
	}
}
```

```php
$magic = new MyMagicContainer;

$magic->foo = 'foo';

var_dump($magic->foo); // -> foo: foobar

```

#### `Enumerable`
```php
class MyEnumerableContainer{
	use Enumerable;

	public function __construct(array $data){
		$this->array = $data;
	}
}
```

```php
$enum = new MyEnumerableContainer($data);

$enum
	->__each(function($value, $index){
		// do stuff
		
		$this->array[$index] = $stuff;
	})
	->__reverse()
	->__to_array()
;

$arr = $enum->__map(function($value, $index){
	// do stuff
	
	return $stuff;
});

$enum;

```

#### `Env`
```
# example .env
FOO=bar
BAR=foo
WHAT=${BAR}-${FOO}
```

```php
class MyClass{
	use Env;
	
	protected $foo;
	
	public function __construct(){
		// load and overwrite existing vars, require var "WHAT"
		$this->__loadEnv(__DIR__.'/../config', '.env', ['what']);
		
		// will not overwrite
		$this->__addEnv(__DIR__.'/../config', '.env', false, ['what']); 
		
		$this->foo = $_ENV['WHAT']; // -> foo-bar
		// or
		$this->foo = $this->__getEnv('WHAT');
	}
}
```

```php
$env = new DotEnv(__DIR__.'/../config', '.env');
$env->load(['foo']); // foo is required

$foo = $env->get('FOO'); // -> bar

$foo = $env->set('foo', 'whatever');
$foo = $env->get('FOO'); // -> whatever
```
