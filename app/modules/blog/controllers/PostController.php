<?php

namespace App\modules\blog\controllers;

use App\components\Controller;
use App\components\View;
use App\modules\blog\models\Blog;
use Micro\mvc\models\Query;

class PostController extends Controller
{
    public function filters()
    {
        return [
            [
                'class' => '\Micro\filter\AccessFilter',
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
        $crt = new Query($this->container->db);
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

        /** @var array $blogData */
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

        $blog->name = 'setup-er';

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
