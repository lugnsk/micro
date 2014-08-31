<?php

/** @var Micro\db\Model $model */

echo \Micro\web\helpers\Html::heading(1, 'Создание статьи');

echo $this->renderPartial('_form', ['model' => $model]);