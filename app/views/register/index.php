<?php
/** @var App\controllers\RegisterController $this */
/** @var App\models\User $model */

echo \Micro\web\helpers\Html::script( $model->getClient() );
?>
<h2>Регистрация</h2>


<?php
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

    $this->endWidget('\Micro\widgets\FormWidget'); ?>