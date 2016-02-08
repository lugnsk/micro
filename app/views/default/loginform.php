<?php

use Micro\Web\Html;

return [
    'description' => Html::heading(2, 'Входилка'),
    'elements' => [
        'login' => ['type' => 'textFieldRow'],
        'password' => ['type' => 'passwordFieldRow']
    ],
    'buttons' => ['login' => ['type' => 'submit', 'label' => 'Войти']]
];
