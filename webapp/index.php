<?php

// Activate error page
//define('DEBUG_MICRO', true);

// Configs
$config = require __DIR__ . '/../app/configs/index.php';

// Get micro
require $config['MicroDir'] . '/base/Autoload.php';
require $config['MicroDir'] . '/Micro.php';

// Run application
\Micro\Micro::getInstance($config)->run();