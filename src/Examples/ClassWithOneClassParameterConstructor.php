<?php

namespace Uny\Examples;

class ClassWithOneClassParameterConstructor {

	private $value ;

	public function __construct( Example $value ){
		$this->value = $value ;
	}

	public function getValue(){
		return $this->value;
	}
}