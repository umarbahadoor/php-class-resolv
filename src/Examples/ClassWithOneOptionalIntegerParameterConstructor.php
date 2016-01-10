<?php

namespace Uny\Examples;

class ClassWithOneOptionalIntegerParameterConstructor {

	private $value ;

	public function __construct( int $value = 99 ){
		$this->value = $value ;
	}

	public function getValue(){
		return $this->value;
	}
}