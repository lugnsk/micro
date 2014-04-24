<?= MHtml::beginForm(''); ?>

<div class="row">
	<?= MHtml::label('Название'); ?>
	<?= MHtml::textField('Blog[name]', $model->name, array('required'=>true)); ?>
</div>

<div class="row">
	<?= MHtml::label('Описание'); ?>
	<?= MHtml::textArea('Blog[content]', $model->content, array('required'=>true)); ?>
</div>

<div class="row actions">
	<?= MHtml::submitButton( ($model->isNewRecord()) ? 'Создать' : 'Обновить' ); ?>
</div>

<?= MHtml::endForm(); ?>