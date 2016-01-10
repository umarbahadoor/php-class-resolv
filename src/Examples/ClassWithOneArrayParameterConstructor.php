<?php

namespace Uny\Examples;

class ClassWithOneArrayParameterConstructor {

	private $value ;

	public function __construct( callable $value ){
		$this->value = $value ;
	}

	public function getValue(){
		return $this->value;
	}
}