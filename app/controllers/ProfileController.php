<?php

namespace App\controllers;

use App\components\Controller;
use App\components\View;
use Micro\base\Registry;
use App\models\User;
use Micro\db\Query;

class ProfileController extends Controller
{
    public function filters()
    {
        return [
            [
                'class'=>'\Micro\filters\AccessFilter',
                'actions'=>['index'],
                'rules'=>[
                    [
                        'allow'     =>false,
                        'actions'   =>['index'],
                        'users'     =>['?'],
                        'message'   =>'Only for authorized!'
                    ],
                ]
            ],
        ];
    }

    public function actionIndex()
    {
        $query = new Query;
        $query->addWhere('id = :id');
        $query->params = [':id' => Registry::get('session')->UserID];

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

        $v = new View;
        $v->addParameter('user',$user);
        return $v;
    }
}