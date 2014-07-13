<?php

class ProfileController extends Controller
{
	public function actionIndex() {
		if (MRegistry::get('user')->isGuest()) {
			$this->redirect('/');
		}

		$query = new MQuery;
		$query->addWhere('id = :id');
		$query->params = array(':id'=>$_SESSION['UserID']);

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

		echo $this->render('index', array('user'=>$user));
	}
}