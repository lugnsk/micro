<?= \Micro\Web\Html::openTag('div', ['class' => 'menu']) ?>
<?= implode(' ', $links) ?>
<?= \Micro\Web\Html::closeTag('div');
