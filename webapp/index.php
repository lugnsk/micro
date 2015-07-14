<?php

// Get micro
require __DIR__ . '/../micro/Micro.php';

// Get kernel
$app = new \Micro\Micro(__DIR__ . '/../app', __DIR__ . '/../micro');

// Get request
$request = new \Micro\web\Request;

// Run framework
$response = $app->run($request);
$response->send();

// Kill application
$app->terminate();
