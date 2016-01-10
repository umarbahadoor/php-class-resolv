<?php

namespace Uny\Examples;

use Uny\Examples\ControllerInterface;

class Example implements ExampleInterface {

	public function getModel(ControllerInterface $controller){
		return $controller->getModel();
	}

}