<?php

// Default session
return [
    'class' => '\Micro\web\Session',
    'arguments' => [
        'container' => '@this',
        'autoStart' => true
    ]
];