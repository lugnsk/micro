<?= \Micro\wrappers\Html::openTag('div', ['class' => 'menu']) ?>
<?= implode(' ', $this->menu) ?>
<?= \Micro\wrappers\Html::closeTag('div');