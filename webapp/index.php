<?php

// Get micro
require $_SERVER['DOCUMENT_ROOT'] . '/../micro/Micro.php';

// Get kernel
$app = new \Micro\Micro( $_SERVER['DOCUMENT_ROOT']. '/../app', $_SERVER['DOCUMENT_ROOT']. '/../micro' );

// Get request
$request = new \Micro\web\Request;

// Run framework
$response = $app->run($request);
$response->send();

// Kill application
$app->terminate($request, $response);
