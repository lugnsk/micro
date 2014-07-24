<?php

namespace App\modules\blog\models;

use Micro\db\MModel;

class Blog extends MModel
{
	public $name;
	public $content;

	static public function tableName() {
		return 'blogs';
	}
}