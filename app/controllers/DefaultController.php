<?php

namespace App\controllers;

use App\components\Controller;
use App\components\View;
use App\models\LoginFormModel;
use Micro\base\Registry;
use Micro\Micro;
use Micro\web\FormBuilder;
use Micro\wrappers\Html;

class DefaultController extends Controller
{
    public function filters()
    {
        return [
            [
                'class' => '\Micro\filters\AccessFilter',
                'actions' => ['login', 'logout', 'index', 'error'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['index'],
                        'users' => ['@'],
                        'message' => 'Only for not authorized!'
                    ],
                    [
                        'allow' => false,
                        'actions' => ['login'],
                        'users' => ['@'],
                        'message' => 'Not authorized only'
                    ],
                    [
                        'allow' => false,
                        'actions' => ['logout'],
                        'users' => ['?'],
                        'message' => 'Authorized only'
                    ]
                ]
            ],
            [
                'class' => '\Micro\filters\CsrfFilter',
                'actions' => ['login']
            ],
            [
                'class' => '\Micro\filters\XssFilter',
                'actions' => ['index', 'login', 'logout'],
                'clean' => '*'
            ]
        ];
    }

    public function actionIndex()
    {
        return new View($this->container);
    }

    public function actionLogin()
    {
        $form = new FormBuilder(
            include $this->container->kernel->getAppDir() . '/views/default/loginform.php',
            new LoginFormModel($this->container),
            'POST'
        );

        if ($post = $this->container->request->getPostVar('LoginFormModel')) {
            $form->setModelData($post);
            if ($form->validateModel() && $form->getModel()->logined()) {
                $this->redirect('/profile');
            }
        }

        $v = new View($this->container);
        $v->addParameter('form', $form);
        return $v;
    }

    public function actionError()
    {
        $result = null;
        if ($errors = $this->container->request->getPostVar('errors')) {
            foreach ($errors AS $err) {
                $result .= Html::heading(3, $err, ['class' => 'text-danger bg-danger']);
            }
        }
        $v = new View( $this->container );
        $v->data = $result ?: 'undefined error';
        return $v;
    }

    public function actionLogout()
    {
        $this->container->session->destroy();
        $this->redirect('/');
    }
}