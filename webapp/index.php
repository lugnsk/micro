<?php

// Constants
if (!defined('DIRECTORY_SEPARATOR')) {
	define('DIRECTORY_SEPARATOR', (PHP_OS == 'Windows') ? '\\' : '/' );
}

// Configs
$config = require '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config.php';

// Get micro
require $config['MicroDir'] . DIRECTORY_SEPARATOR . 'Micro.php';

// Run application
Micro::getInstance($config)->run();