<?php

namespace App\controllers;

use App\components\Controller;
use App\models\User;
use Micro\auth\FileRbac;
use Micro\base\Registry;

class RegisterController extends Controller
{
    public function actionIndex()
    {
        $uid = Registry::get('session')->UserID;
        if (!$uid) {
            $this->redirect('/profile');
        }

        /** @var FileRbac $rbac */
        $rbac = Registry::get('permission');
        $rbac->check(1, 'register'); // hack

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