<?php

use \Micro\web\helpers\Html;

/** @var App\controllers\RegisterController $this */
/** @var App\models\User $model */

echo Html::script( $model->getClient() );

echo Html::heading(2, 'Регистрация');

/** @var \Micro\web\Form $form */
$form = $this->beginWidget('\Micro\widgets\FormWidget',[
    'method'=>'post',
    'action'=>'/register/post'
]);

echo $form->textFieldRow($model, 'email');
echo $form->textFieldRow($model, 'login');
echo $form->passwordFieldRow($model, 'pass');
echo $form->textFieldRow($model, 'fio');
echo \Micro\web\helpers\Html::submitButton('Зарегистрироваться');

$this->endWidget('\Micro\widgets\FormWidget');