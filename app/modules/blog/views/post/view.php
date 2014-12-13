<?php

use \Micro\wrappers\Html;

/** @var \App\modules\blog\models\Blog $model */

echo Html::href('назад', '/blog/post');
echo Html::heading(1, $model->name);
echo Html::openTag('p'), $model->content, Html::closeTag('p');