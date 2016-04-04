<?php

use Micro\Web\Html\Html;

/** @var string $content */
/** @var \App\Components\View $this */

(new \App\Assets\AppAsset($this))->publish();
?>
<?= Html::doctype('html5') ?>
<html>
<head>
    <?= Html::charset('utf-8') ?>
    <?= Html::meta('language', 'ru') ?>
    <?= Html::favicon('/favicon.ico') ?>
    <?= Html::meta('viewport', 'width=device-width, initial-scale=1.0') ?>
    <?= Html::title($this->title) ?>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9" style="line-height: 29px">
            <b><?= $this->container->slogan ?></b>
        </div>
        <div class="col-md-3 text-right">
            <img src="/images/v-line.png"/>
            <a href="https://vk.com/microphp" target="_blank"><img src="/images/vk.png"/></a>
            <img src="/images/v-line.png"/>
            <a href="https://www.facebook.com/groups/557836681014341/" target="_blank"><img src="/images/fb.png"/></a>
            <img src="/images/v-line.png"/>
            <a href="https://twitter.com/microcmf" target="_blank"><img src="/images/tw.png"/></a>
            <img src="/images/v-line.png"/>
        </div>
    </div>
    <div class="row top-menu">
        <div class="brand col-md-2">
            <a href="/"><?= $this->container->company ?></a>
        </div>
        <div class="col-md-7">
            <?= $this->widget('\App\Widgets\MenubarWidget', ['links' => $this->menu]); ?>
        </div>
        <div class="col-md-3">
            <?= $this->widget('\App\Widgets\MenubarWidget', ['links' => $this->user]); ?>
        </div>
    </div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><a href="/"><?= $this->container->company ?></a></li>
                <li class="active"><?= $this->title ?></li>
            </ul>
            <div class="content clearfix"><?= $content ?></div>
        </div>
    </div>
    <div class="row top-menu">
        <div class="col-md-12">&nbsp;</div>
    </div>
    <div class="row">
        <div class="col-md-9">
            &copy; <?= $this->container->company ?> <?= date('Y') ?>
        </div>
        <div class="col-md-3 text-right">
            <img src="/images/v-line.png"/>
            <a href="https://vk.com/microphp" target="_blank"><img src="/images/vk.png"/></a>
            <img src="/images/v-line.png"/>
            <a href="https://www.facebook.com/groups/557836681014341/" target="_blank"><img src="/images/fb.png"/></a>
            <img src="/images/v-line.png"/>
            <a href="https://twitter.com/microcmf" target="_blank"><img src="/images/tw.png"/></a>
            <img src="/images/v-line.png"/>
        </div>
    </div>
    </div>
</body>
</html>
