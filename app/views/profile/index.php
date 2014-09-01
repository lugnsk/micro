<?php
/** @var \App\models\User $user */
?>
<h1>Личный кабинет (<?= $user->login; ?>)
    <small><?= $user->fio; ?></small>
</h1>

<?= \Micro\web\helpers\Html::beginForm('', 'post', ['name'=>'Setup_form']) ?>
    <p><label>ФИО</label><input type="text" name="Setup[fio]"/></p>

    <p><label>Новый пароль</label><input type="password" name="Setup[pass]"/></p>

    <p><?= \Micro\web\helpers\Html::submitButton('Обновить') ?></p>
<?= \Micro\web\helpers\Html::endForm() ?>