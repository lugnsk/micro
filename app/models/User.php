<?php

namespace App\models;

use Micro\db\Model;

class User extends Model
{
	static public function tableName() {
		return 'users';
	}
}