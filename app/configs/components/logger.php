<?php

// Logging
return [
    'class' => '\Micro\base\Logger',
    'arguments' => [
        'loggers' => [
            'file' => [
                'class' => '\Micro\loggers\DbLogger',
                'levels' => 'notice, error, emergency, critical, alert, warning, info, debug',
                'table' => 'logs'
            ]
        ]
    ]
];