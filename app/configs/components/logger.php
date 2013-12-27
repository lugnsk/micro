<?php

// Logging
return [
    'class' => '\Micro\base\Logger',
    'loggers' => [
        'file' => [
            'class' => '\Micro\loggers\DbLogger',
            'levels' => 'notice, error, emergency, critical, alert, warning, info, debug',
            'table' => 'logs'
        ]
    ]
];