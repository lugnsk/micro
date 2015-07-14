<?php

// Get micro
require __DIR__ . '/micro/Micro.php';

// Get kernel
$app = new \Micro\Micro(__DIR__ . '/app', __DIR__ . '/micro');
$app->loader();
