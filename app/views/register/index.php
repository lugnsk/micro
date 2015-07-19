<?php

use Micro\web\Html;

/** @var App\components\View $this */
/** @var App\models\User $model */

echo Html::heading(2, 'Регистрация');

/** @var \Micro\form\Form $form */
$form = $this->beginWidget('\Micro\widget\FormWidget', [
    'method' => 'post',
    'action' => '/register/post',
    'client' => $model->getClient()
]);

echo $form->textFieldRow($model, 'email');
echo $form->textFieldRow($model, 'login');
echo $form->passwordFieldRow($model, 'pass');
echo $form->textFieldRow($model, 'fio');
echo Html::submitButton('Зарегистрироваться');

$this->endWidget('\Micro\widget\FormWidget');