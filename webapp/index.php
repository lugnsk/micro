<?php

// Configs
$config = require '../app/config.php';

// Get micro
require $config['MicroDir'] . '/base/MAutoload.php';
require $config['MicroDir'] . '/Micro.php';

// Run application
\Micro\Micro::getInstance($config)->run();