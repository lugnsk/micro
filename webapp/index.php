<?php

// Get micro
require __DIR__ . '/../app/Application.php';

// Get kernel
$app = new \App\Application();

// Run framework
$app->run(new \Micro\Web\Request)->send();

// Kill application
$app->terminate();
