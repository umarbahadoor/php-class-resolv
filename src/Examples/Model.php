<?php

namespace Uny\Examples;

class Model {

	private $id;

	function __construct(int $id) {
		$this->id = $id;
	}

	public function setId( $id ){
		$this->id = $id;
	}

	public function getId(){
		return $this->id;
	}

}
