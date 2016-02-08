<?php
use Micro\Web\Html;

/** @var \App\Components\View $this */
$this->title .= ' - Главная';
?>
<?= Html::heading(1, 'Simple app'); ?>
<?= Html::openTag('p') ?>This site is a simple<?= Html::closeTag('p'); ?>
