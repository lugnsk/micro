<?php

namespace App\modules\blog\controllers;

use App\components\Controller;
use App\components\View;
use App\modules\blog\models\Blog;
use Micro\db\Query;

class PostController extends Controller
{
    public function filters()
    {
        return [
            [
                'class' => '\Micro\filters\AccessFilter',
                'actions' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['create', 'update', 'delete'],
                        'users' => ['?'],
                        'message' => 'Only for authorized!'
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'users' => ['*'],
                        'message' => 'View for all'
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
        $crt = new Query($this->container);
        $crt->table = Blog::tableName();
        $crt->order = 'id DESC';

        $v = new View($this->container);
        $v->addParameter('blogs', $crt);
        $v->addParameter('page', $this->container->request->getQueryVar('page') ?: 0);

        return $v;
    }

    public function actionView()
    {
        $blog = Blog::findByPk($this->container->request->getQueryVar('id'), $this->container);
        $v = new View($this->container);
        $v->addParameter('model', $blog);

        return $v;
    }

    public function actionCreate()
    {
        $blog = new Blog($this->container);

        if ($blogData = $this->container->request->getPostVar('Blog')) {
            $blog->name = $blogData['name'];
            $blog->content = $blogData['content'];

            if ($blog->save()) {
                $this->redirect('/blog/post/' . $blog->id);
            }
        }

        $v = new View($this->container);
        $v->addParameter('model', $blog);

        return $v;
    }

    public function actionUpdate()
    {
        $blog = Blog::findByPk($this->container->request->getQueryVar('id'), $this->container);

        $blog->name = 'setupher';

        return $blog->save();
    }

    public function actionDelete()
    {
        $blog = Blog::findByPk(
            $this->container->request->getQueryVar('id'),
            $this->container
        );

        return $blog->delete();
    }
}