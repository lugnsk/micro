<?php

// Constants
if (empty(DIRECTORY_SEPARATOR)) {
	define('DIRECTORY_SEPARATOR', (PHP_OS == 'Windows') ? '\\' : '/' );
}

// Configs
$config = require_once '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config.php';

// Get micro
require_once $config['MicroDir'] . DIRECTORY_SEPARATOR . 'Micro.php';

// Run application
Micro::getInstance($config)->run();