<?php

namespace App\controllers;

use Micro\base\Registry;
use Micro\web\MFormBuilder;
use App\models\LoginFormModel;
use Micro\Micro;

class DefaultController extends \App\components\Controller
{
	public function actionIndex() {
		echo $this->render('index');
	}

	public function actionLogin() {
		if (!Registry::get('user')->isGuest()) {
			$this->redirect('/');
		}

		$form = new MFormBuilder(
			include Micro::getInstance()->config['AppDir'].'/views/default/loginform.php',
			new LoginFormModel(),
			'POST'
		);

		if (isset($_POST['LoginFormModel'])) {
			$form->setModelData($_POST['LoginFormModel']);
			if ($form->validateModel() AND $form->getModel()->logined()) {
				$this->redirect('/profile');
			}
		}
		echo $this->render('login', array('form'=>$form));
	}

	public function actionLogout() {
		Registry::get('session')->destroy();
		$this->redirect('/');
	}
}