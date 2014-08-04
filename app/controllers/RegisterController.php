<?php

namespace App\controllers;

use App\components\Controller;
use App\models\User;

class RegisterController extends Controller
{
    public function actionIndex()
    {
        if (isset($_SESSION['UserID']) AND !empty($_SESSION['UserID'])) {
            $this->redirect('/profile');
        }

        echo $this->render('index', ['model'=>new User]);
    }

    public function actionSuccess()
    {
        echo $this->render('success');
    }

    public function actionError()
    {
        echo $this->render('error');
    }

    public function actionPost()
    {
        if (isset($_POST['User'])) {
            $user = new User;
            $user->setModelData($_POST['User']);
            $user->pass = md5($_POST['User']['pass']);

            if ($user->save()) {
                $this->redirect('/register/success');
            }
            $this->redirect('/register/error');
        }
        $this->redirect('/register');
    }
}