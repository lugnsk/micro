<?php

/** @var \Micro\Mvc\Models\Model $model */

echo \Micro\Web\Html\Html::heading(1, 'Создание статьи');

echo $this->renderPartial('_form');
