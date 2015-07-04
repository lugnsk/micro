<?php

namespace App\controllers;

use App\components\Controller;
use App\components\View;
use App\models\User;
use Micro\base\Registry;
use Micro\db\Query;

class ProfileController extends Controller
{
    public function filters()
    {
        return [
            [
                'class' => '\Micro\filters\AccessFilter',
                'actions' => ['index'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['index'],
                        'users' => ['?'],
                        'message' => 'Only for authorized!'
                    ]
                ]
            ],
            [
                'class' => '\Micro\filters\CsrfFilter',
                'actions' => ['index']
            ],
            [
                'class' => '\Micro\filters\XssFilter',
                'actions' => ['index'],
                'clean' => '*'
            ]
        ];
    }

    public function actionIndex()
    {
        $user = User::findByPk($this->container->user->getID(), $this->container);
        if (!$user) {
            $this->redirect('/logout');
        }

        if ($setup = $this->container->request->getPostVar('Setup')) {
            if (!empty($setup['pass'])) {
                $user->pass = md5($setup['pass']);
            }

            if (!empty($setup['fio'])) {
                $user->fio = $setup['fio'];
            }

            $user->save();
        }

        $v = new View($this->container);
        $v->addParameter('user', $user);
        return $v;
    }
}