<?php
/**
 * Trait ClassLoader
 *
 * @filesource   ClassLoader.php
 * @created      13.11.2017
 * @package      chillerlan\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Traits;

use Exception, ReflectionClass;

trait ClassLoader{

	/**
	 * Instances an object of $class/$type with an arbitrary number of $params
	 *
	 * @param string $class  class FQCN
	 * @param string $type   class/parent/interface FQCN
	 *
	 * @param mixed $params [optional] the following arguments will be passed to the $class constructor
	 *
	 * @return mixed of type $type
	 * @throws \Exception
	 */
	public function loadClass(string $class, string $type = null, ...$params){
		$type = $type ?? $class;

		try{
			$reflectionClass = new ReflectionClass($class);
			$reflectionType  = new ReflectionClass($type);
		}
		catch(Exception $e){
			throw new TraitException('ClassLoader: '.$e->getMessage());
		}


		if($reflectionType->isTrait()){
			throw new TraitException($class.' cannot be an instance of trait '.$type);
		}

		if($reflectionClass->isAbstract()){
			throw new TraitException('cannot instance abstract class '.$class);
		}

		if($reflectionClass->isTrait()){
			throw new TraitException('cannot instance trait '.$class);
		}

		if($class !== $type){

			if($reflectionType->isInterface() && !$reflectionClass->implementsInterface($type)){
				throw new TraitException($class.' does not implement '.$type);
			}
			elseif(!$reflectionClass->isSubclassOf($type)) {
				throw new TraitException($class.' does not inherit '.$type);
			}

		}

		try{
			$object = $reflectionClass->newInstanceArgs($params);

			if(!$object instanceof $type){
				throw new TraitException('how did u even get here?'); // @codeCoverageIgnore
			}

			return $object;
		}
		catch(Exception $e){
			throw new TraitException('ClassLoader: '.$e->getMessage());
		}

	}

}
