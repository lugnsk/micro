<?php

use Micro\web\helpers\MHtml;

return array(
	'description'=>MHtml::heading(2,'Входилка'),

	'elements'=>array(
		'login'=>array('type'=>'textFieldRow'),
		'password'=>array('type'=>'passwordFieldRow'),
	),
	'buttons'=>array(
		'login'=>array('type'=>'submit','label'=>'Войти'),
	)
);