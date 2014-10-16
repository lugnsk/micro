<?php
use \Micro\wrappers\Html;

/** @var \App\controllers\DefaultController $this */
$this->title .= ' - Главная';
?>
<?= Html::heading(1, 'Simple app') ?>
<?= Html::openTag('p') ?>This site is a simple<?= Html::closeTag('p') ?>