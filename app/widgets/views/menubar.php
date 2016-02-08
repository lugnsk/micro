<?= \Micro\Web\Html::openTag('div', ['class' => 'menu']) ?>
<?= implode(' ', $this->menu) ?>
<?= \Micro\Web\Html::closeTag('div');
