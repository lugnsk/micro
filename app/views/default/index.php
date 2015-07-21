<?php
use Micro\web\Html;

/** @var \App\components\View $this */
$this->title .= ' - Главная';
?>
<?= Html::heading(1, 'Simple app'); ?>
<?= Html::openTag('p') ?>This site is a simple<?= Html::closeTag('p'); ?>
