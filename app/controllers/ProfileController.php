<?php

namespace App\Controllers;

use App\Components\Controller;
use App\Components\View;
use App\Models\User;

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
        $user = User::findByPk($this->container->user->getID(), $this->container);
        if (!$user) {
            $this->redirect('/logout');
        }

        /** @var array $setup */
        if ($setup = $this->container->request->post('Setup')) {
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
