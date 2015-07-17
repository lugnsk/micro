<?= \Micro\web\Html::openTag('div', ['class' => 'menu']) ?>
<?= implode(' ', $this->menu) ?>
<?= \Micro\web\Html::closeTag('div');