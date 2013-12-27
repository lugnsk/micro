<?php

// read components
$components = [];
foreach (scandir(__DIR__ . '/components') AS $fileName) {
    if ($fileName !== '.' AND $fileName !== '..') {
        $components[substr($fileName, 0, -4)] = require __DIR__ . '/components/' . $fileName;
    }
}

return [
    // Directories
    'MicroDir' => __DIR__ . '/../../micro',
    'AppDir' => __DIR__ . '/..',
    // Sitename
    'company' => 'Micro',
    'slogan' => 'simply hmvc php framework',
    // Print run time
    'timer' => true,
    // Language
    'lang' => 'en',
    // Errors
    'errorController' => '\App\controllers\DefaultController',
    'errorAction' => 'error',
    // Setup components
    'components' => $components
];