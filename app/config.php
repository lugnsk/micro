<?php

return [
    // Directories
    'MicroDir' => __DIR__ . '/../micro',
    'AppDir' => __DIR__,
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
                    'class' => '\Micro\loggers\DbLogger',
                    'levels' => 'notice, error, emergency, critical, alert, warning, info, debug',
                    'table' => 'logs',
                ]
            ]
        ],
        // FORM Files
        'files' => [
            'class' => '\Micro\web\Uploader'
        ],
        // Cookie's
        'cookie' => [
            'class' => '\Micro\web\Cookie'
        ],
        // Default session
        'session' => [
            'class' => '\Micro\web\Session',
            'autoStart' => true,
        ],
        // Flash messages
        'flash' => [
            'class' => '\Micro\web\FlashMessage',
            'depends' => 'session'
        ],
        // DataBase
        'db' => [
            'class' => '\Micro\db\DbConnection',
            'connectionString' => 'mysql:host=localhost;dbname=micro',
            'username' => 'micro',
            'password' => 'micro',
            'options' => [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'']
        ],
        // User
        'user' => [
            'class' => '\Micro\web\User',
            'depends' => 'session'
        ],
        // Roles
        'permission' => [
            'class' => '\Micro\auth\DbAcl',
            'roles' => include __DIR__ . '/roles.php'
        ]
    ]
];