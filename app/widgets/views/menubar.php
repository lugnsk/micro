<?= \Micro\web\helpers\Html::openTag('div', ['class'=>'menu']) ?>
    <?= implode(' ', $this->links) ?>
<?= \Micro\web\helpers\Html::closeTag('div') ?>