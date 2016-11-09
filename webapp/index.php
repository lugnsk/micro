<?php

// Run system and get app
require __DIR__ . '/../app/__autoload.php';
require __DIR__ . '/../app/Kernel.php';

// Get kernel
$kernel = new \App\Kernel('debug', false);
$request = \Zend\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

$app = new \Micro\Mvc\MvcApplication($kernel);

// Run framework
$response = $app->run($request);

// Send response
$app->send($response);

// Kill application
$app->terminate();
