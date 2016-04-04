<?php

// Run system and get app
require __DIR__ . '/../app/__autoload.php';
require __DIR__ . '/../app/Application.php';

// Get kernel
$app = new \App\Application('debug', false);

// Run framework
$response = $app->run(new \Micro\Web\Request);

// Send response
$response->send();

// Kill application
$app->terminate();
