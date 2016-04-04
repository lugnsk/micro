<?= \Micro\Web\Html\Html::openTag('div', ['class' => 'menu']) ?>
<?= implode(' ', $links) ?>
<?= \Micro\Web\Html\Html::closeTag('div');
