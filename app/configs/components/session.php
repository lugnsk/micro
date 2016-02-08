<?php

// Default session
return [
    'class' => '\Micro\Web\Session',
    'arguments' => [
        'request' => '@request',
        'autoStart' => true
    ]
];
