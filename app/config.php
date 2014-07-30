<?php

return [
    // Directories
    'MicroDir' => __DIR__ . '/../micro',
    'AppDir' => __DIR__,

    // Sitename
    'company' => 'Micro',
    'slogan' => 'simply hmvc php framework',

    // Default import dir
    'import' => [
        'extensions',
        'components',
        'widgets',
        'models',
    ],

    // Print run time
    'timer' => true,
    // Language
    'lang' => 'en',

    // Setup components
    'components' => [
        // Request manager
        'request' => [
            'class' => '\Micro\web\Request',
            'routes' => [
                '/login' => '/default/login',
                '/logout' => '/default/logout',
                '/login/<num:\d+>/<type:\w+>/<arr:\d{3}>' => '/default/login',

                '/blog/post/index/<page:\d+>' => '/blog/post',
                '/blog/post/<id:\d+>' => '/blog/post/view',
            ],
        ],
        // Logging
        'logger' => [
            'class' => '\Micro\base\Logger',
            'loggers' => [
                'file' => [
                    'class' => '\Micro\loggers\FileLogger',
                    'levels' => 'notice, error, emergency, critical, alert, warning, info, debug',
                    'filename' => __DIR__ . '/temp/application.log',
                ]
            ]
        ],
        // Default session
        'session' => [
            'class' => '\Micro\base\Session',
            'autoStart' => true,
        ],
        // Flash messages
        'flash' => [
            'class' => '\Micro\web\helpers\FlashMessage',
            'depends' => 'session'
        ],
        // DataBase
        'db' => [
            'class' => '\Micro\db\DbConnection',
            'connectionString' => 'mysql:host=localhost;dbname=micro',
            'username' => 'micro',
            'password' => 'micro',
            'charset' => 'utf8'
        ],
        'user' => [
            'class' => '\Micro\web\helpers\User',
            'depends' => 'session'
        ]
    ]
];