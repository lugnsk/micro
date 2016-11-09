<?php

namespace App\Modules\Blog\Controllers;

use App\Components\Controller;
use App\Components\View;
use App\Modules\Blog\Models\Blog;
use Micro\Db\Injector;
use Micro\Mvc\Models\Query;
use Micro\Web\RequestInjector;

/**
 * Class PostController
 * @package App\Modules\Blog\Controllers
 */
class PostController extends Controller
{
    public function filters()
    {
        return [
            [
                'class' => '\Micro\Filter\AccessFilter',
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
        $crt = new Query((new Injector)->getDriver());
        $crt->table = Blog::tableName();
        $crt->order = 'id DESC';

        $v = new View();
        $v->addParameter('blogs', $crt);
        $query = (new RequestInjector)->build()->getQueryParams();
        $v->addParameter('page', $query['page'] ?: 0);

        return $v;
    }

    public function actionView()
    {
        $query = (new RequestInjector)->build()->getQueryParams();
        $blog = Blog::findByPk($query['id']);
        $v = new View();
        $v->addParameter('model', $blog);

        return $v;
    }

    public function actionCreate()
    {
        $blog = new Blog();

        $body = (new RequestInjector)->build()->getParsedBody();
        /** @var array $blogData */
        if ($blogData = $body['Blog']) {
            $blog->name = $blogData['name'];
            $blog->content = $blogData['content'];

            if ($blog->save()) {
                return $this->redirect('/blog/post/' . $blog->id);
            }
        }

        $v = new View();
        $v->addParameter('model', $blog);

        return $v;
    }

    public function actionUpdate()
    {
        $query = (new RequestInjector)->build()->getQueryParams();
        $blog = Blog::findByPk($query['id']);

        $blog->name = 'setup-er';

        return $blog->save();
    }

    public function actionDelete()
    {
        $query = (new RequestInjector)->build()->getQueryParams();
        $blog = Blog::findByPk($query['id']);

        return $blog->delete();
    }
}
