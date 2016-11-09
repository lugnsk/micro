<?php

namespace App\Controllers;

use App\Components\Controller;
use App\Components\View;
use App\Models\User;
use Micro\Web\RequestInjector;

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
        $body = (new RequestInjector)->build()->getParsedBody();

        if (!empty($body['User'])) {
            $user = new User();
            $user->setModelData($body['User']);
            $user->pass = md5($user->pass);

            if ($user->validate() && $user->save()) {
                return $this->redirect('/register/success');
            }
            return $this->redirect('/register/error');
        }
        return $this->redirect('/register');
    }
}
