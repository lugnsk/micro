<?php

namespace App\models;

use Micro\db\MModel;

class User extends MModel
{
	static public function tableName() {
		return 'users';
	}
}