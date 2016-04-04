<?php

use Micro\Web\Html\Html;

/** @var App\Components\View $this */
/** @var App\Models\User $model */

echo Html::heading(2, 'Регистрация');

/** @var \Micro\Form\Form $form */
$form = $this->beginWidget('\Micro\Widget\FormWidget', [
    'method' => 'post',
    'action' => '/register/post',
    'client' => $model->getClient()
]);

echo $form->textFieldRow($model, 'email');
echo $form->textFieldRow($model, 'login');
echo $form->passwordFieldRow($model, 'pass');
echo $form->textFieldRow($model, 'fio');
echo Html::submitButton('Зарегистрироваться');

$this->endWidget('\Micro\Widget\FormWidget');
