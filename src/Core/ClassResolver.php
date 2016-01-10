<?php

namespace Uny\Core;

use Uny\Trials\Example;

class ClassResolver
{

	private $bindings;
	private $baseNamespace;

	public function __construct( array $bindings = [], $baseNamespace = '' ){
		$this->bindings = $bindings ;
		$this->baseNamespace = $baseNamespace ;
	}

    public function resolve($className)
    {
    	if( isset($this->bindings[$className] )){
    		return $this->handleBoundClass($className);
    	}

    	if( class_exists($className, true )){
    		return $this->createInstance($className);
    	}

    	if( class_exists($this->baseNamespace.'\\'.$className, true )){
    		return $this->createInstance($this->baseNamespace.'\\'.$className);
    	}

        throw new \Exception("Unresolvable Class: " . $className);
        
    }

    public function resolveClosure( callable $closure){
        
        $reflectionFunction = $this->getClosureReflection($closure);
        $args = $this->resolveArguments($reflectionFunction);
        $value = call_user_func_array( $closure, $args);
        if( is_callable($value) ){
            return $this->resolveClosure($value);
        }

        return $value;
        
    }

    private function resolveArguments(\ReflectionFunctionAbstract $function){
        $params = $function->getParameters(); 
        return array_map( [$this, 'handleSingleParameter'], $params ) ;
    }

    private function handleBoundClass($alias){

    	$resolvedName = $this->bindings[$alias];
    	
    	if( is_string($resolvedName) ){
    		return $this->resolve($resolvedName);
    	}

    	if( is_callable($resolvedName)){
    		return $this->resolveClosure($resolvedName);
    	}

    	if( is_object($resolvedName)){
    		return $resolvedName;
    	}

        return null;
        
    }


    private function getClosureReflection( $callable ){
    	if(is_array($callable)){
			list($object, $methodName) = $callable ;
			return new \ReflectionMethod($object, $methodName);
    	}
    	
    	return new \ReflectionFunction($callable);
    	
    }

    private function createInstance($className){
    	
    	$reflectionClass = new \ReflectionClass($className);

    	$constructor = $reflectionClass->getConstructor();
    	
    	if(!$constructor){
    		return $reflectionClass->newInstance();
    	}

    	$args = $this->resolveArguments($constructor);
    	
    	return $reflectionClass->newInstanceArgs($args);
    }

    private function handleSingleParameter($param){

    	if($param->getType()->isBuiltin()){
			return $this->handlePrimitiveTypes($param);
		}

		$parameterClass = $param->getClass();
		if( !$parameterClass ){
			return null ;
		}

		return $this->resolve($parameterClass->getName());
		
    }

    private function handlePrimitiveTypes($param){
	
		$parameterType = (string) $param->getType();
		
		if( $param->isDefaultValueAvailable() ){
			return $param->getDefaultValue() ;
		}

		// Handles the 8 primitive types

		if( 'boolean' === $parameterType ){
			return false ;
		}
	
		if( 'int' === $parameterType ){
			return 0 ;
		}	

		if( 'float' === $parameterType ){
			return 0 ;
		}

		if( 'string' === $parameterType ){
			return '' ;
		}

		if( 'array' === $parameterType ){
			return [] ;
		}
		
		if( 'object' === $parameterType ){
			return new stdObject ;
		}

		if( 'callable' === $parameterType ){
			return function(){} ;
		}

	
		return null; 
    }
}
