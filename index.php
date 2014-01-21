<?php

// Constants
if (DIRECTORY_SEPARATOR == null) {
	define('DIRECTORY_SEPARATOR', '/');
}

// Configs
$config = require_once 'app' . DIRECTORY_SEPARATOR . 'config.php';

// Start application
require_once $config['MicroDir'] . DIRECTORY_SEPARATOR . 'Micro.php';

$micro = Micro::getInstance($config);
$micro->run();

?>