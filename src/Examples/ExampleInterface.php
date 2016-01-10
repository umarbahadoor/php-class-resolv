<?php

namespace Uny\Examples;

use Uny\Examples\ControllerInterface;

interface ExampleInterface {
	public function getModel(ControllerInterface $controller);
}