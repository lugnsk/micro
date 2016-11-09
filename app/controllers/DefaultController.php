<?php

namespace App\Controllers;

use App\Components\Controller;
use App\Components\View;
use App\Models\LoginFormModel;
use Micro\Base\KernelInjector;
use Micro\Form\FormBuilder;
use Micro\Web\Html\Html;
use Micro\Web\RequestInjector;
use Micro\Web\SessionInjector;

/**
 * Class DefaultController
 * @package App\Controllers
 */
class DefaultController extends Controller
{
    public function filters()
    {
        return [
            [
                'class' => '\Micro\Filter\AccessFilter',
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
                'class' => '\Micro\Filter\CsrfFilter',
                'actions' => ['login']
            ],
            [
                'class' => '\Micro\Filter\XssFilter',
                'actions' => ['index', 'login', 'logout'],
                'clean' => '*'
            ]
        ];
    }

    public function actionIndex()
    {
        return new View;
    }

    public function actionLogin()
    {
        /** @noinspection PhpIncludeInspection */
        $form = new FormBuilder(
            include (new KernelInjector)->build()->getAppDir() . '/views/default/loginform.php',
            new LoginFormModel(),
            'POST'
        );
        $body = (new RequestInjector)->build()->getParsedBody();

        if (!empty($body['LoginFormModel'])) {
            $form->setModelData($body['LoginFormModel']);
            /** @noinspection PhpUndefinedMethodInspection */
            if ($form->validateModel() && $form->getModel()->logined()) {
                return $this->redirect('/profile');
            }
        }

        $v = new View();
        $v->addParameter('form', $form);

        return $v;
    }

    public function actionError()
    {
        $result = null;
        $body = (new RequestInjector())->build()->getParsedBody();

        if (!empty($body['error'])) {
            $result .= Html::heading(3, $body['error']->getMessage(), ['class' => 'text-danger bg-danger']);
        }
        $v = new View();
        $v->data = $result ?: 'undefined error';

        return $v;
    }

    public function actionLogout()
    {
        (new SessionInjector())->build()->destroy();
        return $this->redirect('/');
    }
}
