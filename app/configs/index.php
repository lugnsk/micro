<?php

// read components
$components = [];
foreach (scandir(__DIR__ . '/components') AS $fileName) {
    if ($fileName !== '.' AND $fileName !== '..') {
        /** @noinspection PhpIncludeInspection */
        $components[substr($fileName, 0, -4)] = require __DIR__ . '/components/' . $fileName;
    }
}

return [
    // Site name
    'company' => 'Micro',
    'slogan' => 'simply hmvc php framework',

    // Language
    'lang' => 'en',

    // Errors
    'errorController' => '\App\Controllers\DefaultController',
    'errorAction' => 'error',

    // Setup components
    'components' => $components
];
