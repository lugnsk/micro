<?php

namespace App\controllers;

use App\components\Controller;
use Micro\base\Registry;
use App\models\User;
use Micro\db\Query;

class ProfileController extends Controller
{
	public function actionIndex() {
		if (Registry::get('user')->isGuest()) {
			$this->redirect('/');
		}

		$query = new Query;
		$query->addWhere('id = :id');
		$query->params = [':id'=>$_SESSION['UserID']];

		$user = User::finder($query, true);

		if (!$user) {
			$this->redirect('/logout');
		}

		if (isset($_POST['Setup'])) {
			$form = $_POST['Setup'];
			if (!empty($form['pass'])) {
				$user->pass = md5($form['pass']);
			}

			if (!empty($form['fio'])) {
				$user->fio = $form['fio'];
			}

			$user->save();
		}

		echo $this->render('index', ['user'=>$user]);
	}
}