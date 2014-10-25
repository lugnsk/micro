<?= \Micro\wrappers\Html::openTag('div', ['class' => 'menu']) ?>
<?= implode(' ', $this->links) ?>
<?= \Micro\wrappers\Html::closeTag('div') ?>