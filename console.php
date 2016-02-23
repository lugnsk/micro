<?php

require __DIR__ . '/micro/Micro.php';

$app = new \Micro\Micro(__DIR__ . '/app', __DIR__ . '/micro');

$app->run(new \Micro\Web\Request)->send();

$app->terminate();
