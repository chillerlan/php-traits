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
	 * @return object of type $type
	 * @throws \Exception
	 */
	public function loadClass(string $class, string $type = null, ...$params){
		$type = $type === null ? $class : $type;

		try{
			$reflectionClass = new ReflectionClass($class);
			$reflectionType  = new ReflectionClass($type);

			if($reflectionType->isTrait()){
				trigger_error($class.' cannot be an instance of trait '.$type);
			}

			if($reflectionClass->isAbstract()){
				trigger_error('cannot instance abstract class '.$class);
			}

			if($reflectionClass->isTrait()){
				trigger_error('cannot instance trait '.$class);
			}

			if($class !== $type){

				if($reflectionType->isInterface() && !$reflectionClass->implementsInterface($type)){
					trigger_error($class.' does not implement '.$type);
				}
				elseif(!$reflectionClass->isSubclassOf($type)) {
					trigger_error($class.' does not inherit '.$type);
				}

			}

			$object = $reflectionClass->newInstanceArgs($params);

			if(!$object instanceof $type){
				trigger_error('how did u even get here?'); // @codeCoverageIgnore
			}

			return $object;
		}
		catch(Exception $e){
			throw new TraitException('ClassLoader: '.$e->getMessage());
		}

	}

}
