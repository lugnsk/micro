<?php

// Default session
return [
    'class' => '\Micro\web\Session',
    'arguments' => [
        'container' => '@request',
        'autoStart' => true
    ]
];