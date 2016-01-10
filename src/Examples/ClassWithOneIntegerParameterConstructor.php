<?php

namespace Uny\Examples;

class ClassWithOneIntegerParameterConstructor {

	private $value ;

	public function __construct( int $value ){
		$this->value = $value ;
	}

	public function getValue(){
		return $this->value;
	}
}