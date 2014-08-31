<?php

use \Micro\web\helpers\Html;

echo Html::link('назад', '/blog/post');
echo Html::heading(1, $model->name);
echo Html::openTag('p'), $model->content, Html::closeTag('p');