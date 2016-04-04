<?php

/** @var array $links */

echo \Micro\Web\Html\Html::openTag('div', ['class' => 'menu']);
echo implode(' ', $links);
echo \Micro\Web\Html\Html::closeTag('div');
