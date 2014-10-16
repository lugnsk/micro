<?php
use Micro\wrappers\Html;

/** @var \App\modules\Blog\models\Blog $model */
?>
<?= Html::beginForm(''); ?>

    <div class="row">
        <?= Html::label('Название'); ?>
        <?= Html::textField('Blog[name]', $model->name, ['required' => true]); ?>
    </div>

    <div class="row">
        <?= Html::label('Описание'); ?>
        <?= Html::textArea('Blog[content]', $model->content, ['required' => true]); ?>
    </div>

    <div class="row actions">
        <?= Html::submitButton(($model->isNewRecord()) ? 'Создать' : 'Обновить'); ?>
    </div>

<?= Html::endForm(); ?>