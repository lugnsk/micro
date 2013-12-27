<?php

use Micro\wrappers\Html;

return [
    'description' => Html::heading(2, 'Входилка'),
    'elements' => [
        'login' => ['type' => 'textFieldRow'],
        'password' => ['type' => 'passwordFieldRow']
    ],
    'buttons' => ['login' => ['type' => 'submit', 'label' => 'Войти']]
];