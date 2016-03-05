<?php

namespace App\Controllers;

use App\Components\Controller;
use App\Components\View;
use App\Models\User;

/**
 * Class RegisterController
 * @package App\Controllers
 */
class RegisterController extends Controller
{
    public function filters()
    {
        return [
            [
                'class' => '\Micro\Filter\AccessFilter',
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
                'class' => '\Micro\Filter\CsrfFilter',
                'actions' => ['index']
            ],
            [
                'class' => '\Micro\Filter\XssFilter',
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
        if ($userData = $this->container->request->post('User')) {
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
