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

        echo $this->render('index');
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
        if (isset($_POST['Register'])) {
            $post = $_POST['Register'];

            $user = new User;
            $user->email = $post['email'];
            $user->login = $post['login'];
            $user->pass = md5($post['pass']);
            $user->fio = $post['fio'];
            if ($user->save()) {
                $this->redirect('/register/success');
            } else {
                $this->redirect('/register/error');
            }
        } else {
            $this->redirect('/register');
        }
    }
}