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
        $query = new Query($this->container);

        $query->addWhere('id = :id');
        $query->params = ['id' => $this->container->user->getID()];

        $user = User::finder($query, true);
        if (!$user) {
            $this->redirect('/logout');
        }

        if (!empty($_POST['Setup'])) {
            $form = $_POST['Setup'];
            if (!empty($form['pass'])) {
                $user->pass = md5($form['pass']);
            }

            if (!empty($form['fio'])) {
                $user->fio = $form['fio'];
            }

            $user->save();
        }

        $v = new View($this->container);
        $v->addParameter('user', $user);
        return $v;
    }
}