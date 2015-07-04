<?php

namespace App\controllers;

use App\components\Controller;
use App\components\View;
use App\models\User;

class RegisterController extends Controller
{
    public function filters()
    {
        return [
            [
                'class' => '\Micro\filters\AccessFilter',
                'actions' => ['success', 'error', 'index', 'post'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['index', 'success', 'error', 'post'],
                        'users' => ['@'],
                        'message' => 'Only for not authorized!'
                    ]
                ]
            ],
            [
                'class' => '\Micro\filters\CsrfFilter',
                'actions' => ['index']
            ],
            [
                'class' => '\Micro\filters\XssFilter',
                'actions' => ['post'],
                'clean' => '*'
            ]
        ];
    }

    public function actionIndex()
    {
        $v = new View($this->container);
        $v->addParameter('model', new User($this->container));
        return $v;
    }

    public function actionSuccess()
    {
        return new View($this->container);
    }

    public function actionError()
    {
        return new View($this->container);
    }

    public function actionPost()
    {
        if ($userData = $this->container->request->getPostVar('User')) {
            $user = new User($this->container);
            $user->setModelData($userData);
            $user->pass = md5($user->pass);

            if ($user->validate() && $user->save()) {
                $this->redirect('/register/success');
            }
            $this->redirect('/register/error');
        }
        $this->redirect('/register');
    }
}