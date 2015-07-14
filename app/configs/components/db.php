<?php

// DataBase
return [
    'class' => '\Micro\db\DbConnection',
    'arguments' => [
        'connectionString' => 'mysql:host=localhost;dbname=micro',
        'username' => 'micro',
        'password' => 'micro',
        'options' => [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''],
        'container' => '@this',
    ]
];