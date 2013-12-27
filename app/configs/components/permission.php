<?php

// Roles
return [
    'class' => '\Micro\auth\DbAcl',
    'roles' => [ // Permission roles
        'roles' => [
            1 => 'user',
            2 => 'admin'
        ],
        'perms' => [
            1 => 'news_read',
            2 => 'news_create',
            3 => 'news_edit'
        ],
        'role_perms' => [
            1 => [2, 3]
        ]
    ]
];