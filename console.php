<?php

require __DIR__ . '/micro/Micro.php';

$app = new \Micro\Micro(__DIR__ . '/app', __DIR__ . '/micro');

$response = $app->run(new \Micro\web\Request);
$response->send();

$app->terminate();
