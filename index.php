<?php

use Uny\Examples\Example;
use Uny\Core\ClassResolver;
use Uny\Examples\Controller;
use Uny\Examples\ExampleInterface;
use Uny\Examples\ControllerInterface;

require_once 'vendor/autoload.php';

use Pimple\Container;


// $bindings = [
// 	'alias' => Example::class,
// 	ExampleInterface::class => Example::class,
// 	ControllerInterface::class => Controller::class,
// ];

$container = new Container();
$container[ExampleInterface::class] = Example::class;
$container[ControllerInterface::class] = Controller::class;

$resolver = new ClassResolver( $container );

$example = $resolver->resolve( ExampleInterface::class );

$value = $resolver->resolveClosure( [$example, 'getModel'] );

var_dump($value);