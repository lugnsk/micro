<?php

namespace App\modules\blog\models;

use Micro\db\Model;

class Blog extends Model
{
	public $name;
	public $content;

	static public function tableName() {
		return 'blogs';
	}
}