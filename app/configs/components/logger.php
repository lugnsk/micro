<?php

// Logging
return [
    'class' => '\Micro\Logger\Logger',
    'arguments' => [
        'container' => '@this',
        'loggers' => [
            'file' => [
                'class' => '\Micro\Logger\DbLog',
                'levels' => 'notice, error, emergency, critical, alert, warning, info, debug',
                'table' => 'logs'
            ]
        ]
    ]
];
