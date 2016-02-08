<?php

// Logging
return [
    'class' => '\Micro\Base\Logger',
    'arguments' => [
        'loggers' => [
            'file' => [
                'class' => '\Micro\Loggers\DbLogger',
                'levels' => 'notice, error, emergency, critical, alert, warning, info, debug',
                'table' => 'logs'
            ]
        ]
    ]
];
