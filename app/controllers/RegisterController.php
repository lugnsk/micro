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
                'class'=>'\Micro\filters\AccessFilter',
                'actions'=>['success','error','index','post'],
                'rules'=>[
                    [
                        'allow'     =>false,
                        'actions'   =>['index','success','error','post'],
                        'users'     =>['@'],
                        'message'   =>'Only for not authorized!'
                    ],
                ]
            ],
            [
                'class'=>'\Micro\filters\CsrfFilter',
                'actions'=>['index']
            ],
            [
                'class'=>'\Micro\filters\XssFilter',
                'actions'=>['post'],
                'clean'=>'*'
            ]
        ];
    }
    public function actionIndex()
    {
        $v = new View;
        $v->addParameter('model', new User);
        return $v;
    }

    public function actionSuccess()
    {
        return new View;
    }

    public function actionError()
    {
        return new View;
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