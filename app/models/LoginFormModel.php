<?php

namespace App\models;

use Micro\web\MFormModel;
use Micro\db\Query;

class LoginFormModel extends MFormModel
{
	public $login;
	public $password;

	public function attributeLabels() {
		return array(
			'login'=>'Логин',
			'password'=>'Пароль'
		);
	}
	public function rules() {
		return array(
			array('login', 'string', 'min'=>5, 'max'=>16),
			array('password', 'string', 'min'=>6, 'max'=>32)
		);
	}
	public function logined() {
		$query = new Query;
		$query->addWhere('login = :login');
		$query->addWhere('pass = :pass');

		$query->params = array(
			':login' => $this->login,
			':pass' => md5($this->password),
		);

		if ($user = User::finder($query, true)) {
			$_SESSION['UserID'] = $user->id;
			return true;
		} else {
			$this->addError('Логин или пароль не верны.');
			return false;
		}
	}
}