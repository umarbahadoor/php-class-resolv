<?php

namespace Uny\Examples;

use Uny\Examples\Repository;
use Uny\Examples\ControllerInterface;

class Controller implements ControllerInterface {

	private $repository;

	public function __construct(Repository $repository){
		$this->repository = $repository;
	}

	public function getModel(){
		return $this->repository->getModel();
	}
}