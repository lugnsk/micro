<?php

// Configs
$config = require '../app/config.php';

// Get micro
require $config['MicroDir'] . '/Micro.php';

// Run application
Micro::getInstance($config)->run();