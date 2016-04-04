<?php

/** @var \App\Modules\Blog\Models\Blog $model */

use Micro\Web\Html\Html;

echo Html::href('Назад', '/blog/post');
echo Html::heading(1, $model->name);
echo Html::openTag('p'), $model->content, Html::closeTag('p');
