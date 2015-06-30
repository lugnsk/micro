<?php

use Micro\Micro;
use Micro\wrappers\Html;

/** @var string $content */
/** @var \App\components\View $this */

$this->registerScriptFile('/css/jquery.js');
$this->registerCssFile('/css/main.css');
?>
<?= Html::doctype('html5') ?>
<html>
<head>
    <?= Html::charset('utf-8') ?>
    <?= Html::meta('language', 'ru') ?>
    <?= Html::favicon('/favicon.png') ?>
    <?= Html::meta('viewport', 'width=device-width, initial-scale=1.0') ?>
    <?= Html::title($this->title) ?>
</head>
<body>
<div id="container">
    <div id="top">
        <span><?= $this->container->company ?></span> <?= $this->container->slogan ?>
    </div>
    <div id="content">
        <?= $this->widget('\App\widgets\MenubarWidget', ['links' => $this->menu]); ?>
        <?= $content ?>
    </div>
    <div id="footer">
        &copy; <?= $this->container->company ?> <?= date('Y') ?>
    </div>
</div>
</body>
</html>