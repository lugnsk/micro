<?php

/** @var \App\modules\blog\models\Blog $model */

use Micro\web\Html;

echo Html::href('Назад', '/blog/post');
echo Html::heading(1, $model->name);
echo Html::openTag('p'), $model->content, Html::closeTag('p');