<?php

namespace App\controllers;

use App\components\Controller;
use App\components\View;
use App\models\LoginFormModel;
use Micro\form\FormBuilder;
use Micro\web\Html;

class DefaultController extends Controller
{
    public function filters()
    {
        return [
            [
                'class' => '\Micro\filter\AccessFilter',
                'actions' => ['login', 'logout', 'index'],
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
                'class' => '\Micro\filter\CsrfFilter',
                'actions' => ['login']
            ],
            [
                'class' => '\Micro\filter\XssFilter',
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
        /** @noinspection PhpIncludeInspection */
        $form = new FormBuilder(
            include $this->container->kernel->getAppDir() . '/views/default/loginform.php',
            new LoginFormModel($this->container),
            'POST'
        );

        if ($post = $this->container->request->post('LoginFormModel')) {
            $form->setModelData($post);
            /** @noinspection PhpUndefinedMethodInspection */
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
        /** @var \Micro\base\Exception $error */
        if ($error = $this->container->request->post('error')) {
            $result .= Html::heading(3, $error->getMessage(), ['class' => 'text-danger bg-danger']);
        }
        $v = new View($this->container);
        $v->data = $result ?: 'undefined error';

        return $v;
    }

    public function actionLogout()
    {
        $this->container->session->destroy();
        $this->redirect('/');
    }
}
