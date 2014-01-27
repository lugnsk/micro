<?php

class DefaultController extends Controller
{
	public function actionIndex() {
		echo $this->render('index');
	}

	public function actionLogin() {
		if (isset($_SESSION['UserID']) AND !empty($_SESSION['UserID'])) {
			$this->redirect('/profile');
		}

		if (isset($_POST['Login'])) {
			$form = $_POST['Login'];

			$query = new MQuery;
			$query->addWhere('login = :login');
			$query->addWhere('pass = :pass');

			$query->params = array(
				':login' => $form['login'],
				':pass' => md5($form['pass'])
			);

			$user = User::finder($query, true);

			if ($user) {
				$_SESSION['UserID'] = $user->id;
				$this->redirect('/profile');
			}
		}
		echo $this->render('login');
	}

	public function actionLogout() {
		if (isset($_SESSION['UserID'])) {
			unset($_SESSION['UserID']);
		}
		$this->redirect('/');
	}
}