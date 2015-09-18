<?php

/** @var \Micro\mvc\models\Model $model */

echo \Micro\web\Html::heading(1, 'Создание статьи');

echo $this->renderPartial('_form');
