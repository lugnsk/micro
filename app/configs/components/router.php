<?php

return [
    'class' => '\Micro\web\Router',
    'arguments' => [
        'routes' => [
            '/login' => '/default/login',
            '/logout' => '/default/logout',
            '/login/<num:\d+>/<type:\w+>/<arr:\d{3}>' => '/default/login',
            '/blog/post/index/<page:\d+>' => '/blog/post',
            '/blog/post/<id:\d+>' => '/blog/post/view'
        ]
    ]
];