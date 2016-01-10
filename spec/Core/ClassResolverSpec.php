<?php

namespace spec\Uny\Core;

use Prophecy\Argument;
use Uny\Examples\Model;
use PhpSpec\ObjectBehavior;
use Uny\Examples\Example;
use Uny\Examples\ValueObject;

class ClassResolverSpec extends ObjectBehavior
{

	function let(){

		$object = new \Uny\Examples\ClassWithOneCallableParameterConstructor(function(){
			return new Example;
		});

		$bindings = [
			'my_recursive_alias' => 'my_alias',
    		'my_alias' => 'Uny\Examples\Example',
    		'my_callable_alias' => function(Example $e){ return new \Uny\Examples\ClassWithOneClassParameterConstructor($e) ; },
    		'my_deep_callable_alias' => function(Example $e){ return function(Example $e){ return function(Example $e){ return new \Uny\Examples\ClassWithOneClassParameterConstructor($e) ; };};},
    		'my_object_callable_alias' => [$object, 'getValue']
    	];

    	$namespace = 'Uny' ;

    	$this->beConstructedWith($bindings, $namespace );

	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Uny\Core\ClassResolver');
    }

    function it_should_resolve_a_basic_class(){
    	$this->resolve('Uny\Examples\Example')->shouldReturnAnInstanceOf(Example::class);
    }

    function it_should_resolve_classes_with_constructor_arguments(){
    	$this->resolve('Uny\Examples\Model')->shouldReturnAnInstanceOf(Model::class);
    }

    function it_should_throw_an_exception_for_invalid_class(){
    	$this->shouldThrow('\Exception')->during('resolve', ['Uny\Examples\X']);
    }

    function it_should_resolve_classes_with_1_constructor_argument_of_type_int(){
    	$this->resolve('Uny\Examples\ClassWithOneIntegerParameterConstructor')
    			->shouldReturnAnInstanceOf(\Uny\Examples\ClassWithOneIntegerParameterConstructor::class);
    }

    function it_should_resolve_classes_with_1_optional_constructor_argument_of_type_int(){
    	$this->resolve('Uny\Examples\ClassWithOneOptionalIntegerParameterConstructor')
    			->getValue()
    			->shouldReturn(99);
    }

    function it_should_resolve_classes_with_1_constructor_argument_of_type_string(){
    	$this->resolve('Uny\Examples\ClassWithOneStringParameterConstructor')
    			->shouldReturnAnInstanceOf(\Uny\Examples\ClassWithOneStringParameterConstructor::class);
    }

    function it_should_resolve_classes_with_1_constructor_argument_of_type_callable(){
    	$this->resolve('Uny\Examples\ClassWithOneCallableParameterConstructor')
    			->shouldReturnAnInstanceOf(\Uny\Examples\ClassWithOneCallableParameterConstructor::class);
    }    

    function it_should_resolve_classes_with_1_constructor_argument_of_type_array(){
    	$this->resolve('Uny\Examples\ClassWithOneArrayParameterConstructor')
    			->shouldReturnAnInstanceOf(\Uny\Examples\ClassWithOneArrayParameterConstructor::class);
    }

    function it_should_resolve_classes_with_1_constructor_argument_of_class_example(){
    	$this->resolve('Uny\Examples\ClassWithOneClassParameterConstructor')
    			->shouldReturnAnInstanceOf(\Uny\Examples\ClassWithOneClassParameterConstructor::class);
    }

    function it_should_resolve_bound_aliases(){
    	$this->resolve('my_alias')
    			->shouldReturnAnInstanceOf(Example::class);
    }

    function it_should_resolve_bound_recursive_aliases(){
    	$this->resolve('my_recursive_alias')
    			->shouldReturnAnInstanceOf(Example::class);
    }

    function it_should_resolve_bound_callable_aliases(){
    	$this->resolve('my_callable_alias')
    			->shouldReturnAnInstanceOf( \Uny\Examples\ClassWithOneClassParameterConstructor::class);
    }

    function it_should_resolve_deeply_bound_callable_aliases(){
    	$this->resolve('my_deep_callable_alias')
    			->shouldReturnAnInstanceOf( \Uny\Examples\ClassWithOneClassParameterConstructor::class);
    }
   
    function it_should_resolve_bound_object_callable_aliases(){
    	$this->resolve('my_object_callable_alias')
    			->shouldReturnAnInstanceOf(Example::class);
    }
    
    function it_should_resolve_partly_namespaced_classes(){
    	$this->resolve('Examples\Example')
    			->shouldReturnAnInstanceOf(Example::class);
    }


    public function getMatchers()
    {
        return [
            'haveValue' => function ($subject, $value) {
                return $subject->getValue == $value ;
            },
        ];
    }


}
