<?php

namespace App\Controllers;

use App\Components\Controller;
use App\Components\View;
use App\Models\User;
use Micro\Web\RequestInjector;
use Micro\Web\UserInjector;

/**
 * Class ProfileController
 * @package App\Controllers
 */
class ProfileController extends Controller
{
    public function filters()
    {
        return [
            [
                'class' => '\Micro\Filter\AccessFilter',
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
                'class' => '\Micro\Filter\CsrfFilter',
                'actions' => ['index']
            ],
            [
                'class' => '\Micro\Filter\XssFilter',
                'actions' => ['index'],
                'clean' => '*'
            ]
        ];
    }

    public function actionIndex()
    {
        $user = User::findByPk((new UserInjector)->build()->getID());
        if (!$user) {
            return $this->redirect('/logout');
        }

        $body = (new RequestInjector)->build()->getParsedBody();

        /** @var array $setup */
        if ($setup = $body['Setup']) {
            if (!empty($setup['pass'])) {
                $user->pass = md5($setup['pass']);
            }

            if (!empty($setup['fio'])) {
                $user->fio = $setup['fio'];
            }

            $user->save();
        }

        $v = new View();
        $v->addParameter('user', $user);

        return $v;
    }
}
