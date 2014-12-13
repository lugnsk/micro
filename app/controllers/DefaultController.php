<?php

namespace App\controllers;

use Micro\Micro;
use Micro\base\Registry;
use App\components\View;
use Micro\web\FormBuilder;
use App\components\Controller;
use App\models\LoginFormModel;

class DefaultController extends Controller
{
    public function filters() {
        return [
            'xss'=>[
                'class'=>'\Micro\filters\XssFilter',
                'actions'=>['index','login','logout'],
                'clean'=>'*'
            ]
        ];
    }
    public function actionIndex()
    {
        if (Registry::get('permission')->check(1, 'open_index')) { // hack
            //Registry::get('logger')->send('notice', 'Logined user open start page');
        }

        return new View;
    }

    public function actionLogin()
    {
        if (!Registry::get('user')->isGuest()) {
            $this->redirect('/');
        }

        $form = new FormBuilder(
            include Micro::getInstance()->config['AppDir'] . '/views/default/loginform.php',
            new LoginFormModel(),
            'POST'
        );

        if (isset($_POST['LoginFormModel'])) {
            $form->setModelData($_POST['LoginFormModel']);
            if ($form->validateModel() AND $form->getModel()->logined()) {
                $this->redirect('/profile');
            }
        }

        $v = new View;
        $v->addParameter('form', $form);
        return $v;
    }

    public function actionLogout()
    {
        Registry::get('session')->destroy();
        $this->redirect('/');
    }
}