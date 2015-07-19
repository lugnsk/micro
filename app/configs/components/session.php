<?php

// Default session
return [
    'class' => '\Micro\web\Session',
    'arguments' => [
        'request' => '@request',
        'autoStart' => true
    ]
];