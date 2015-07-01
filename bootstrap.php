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
$app->terminate();




// Activate error page
//define('DEBUG_MICRO', true);

// Configs
//$config = require __DIR__ . '/app/configs/index.php';

// Get micro
//require $config['MicroDir'] . '/base/Autoload.php';
//require $config['MicroDir'] . '/Micro.php';

// Run application
//\Micro\Micro::getInstance($config);