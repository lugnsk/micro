<?php use Micro\web\helpers\Html; ?>
<?= Html::beginForm(''); ?>

<div class="row">
	<?= Html::label('Название'); ?>
	<?= Html::textField('Blog[name]', $model->name, array('required'=>true)); ?>
</div>

<div class="row">
	<?= Html::label('Описание'); ?>
	<?= Html::textArea('Blog[content]', $model->content, array('required'=>true)); ?>
</div>

<div class="row actions">
	<?= Html::submitButton( ($model->isNewRecord()) ? 'Создать' : 'Обновить' ); ?>
</div>

<?= Html::endForm(); ?>