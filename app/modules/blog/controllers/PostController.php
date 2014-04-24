<?php

class PostController extends Controller
{
	public function actionIndex() {
		$crt = new MQuery;
		$crt->limit = 10;
		$crt->order = 'id DESC';

		if (!isset($_GET['page'])) {
			$_GET['page'] = 0;
		}
		$crt->ofset = $_GET['page'] * $crt->limit;


		$crt2 = new MQuery;
		$crt2->select = 'COUNT(id)';
		$crt2->table = Blog::tableName();
		$crt2->single = true;
		$num = $crt2->run();

		echo $this->render('index', array(
			'blogs'=>Blog::finder($crt),
			'pages' => ceil($num[0] / 10),
		));
	}

	public function actionView() {
		$crt = new MQuery;
		$crt->addWhere('id = :id');
		$crt->params = array(
			':id' => $_GET['id']
		);
		$blog = Blog::finder($crt, true);

		echo $this->render('view', array('model'=>$blog));
	}

	public function actionCreate() {
		$blog = new Blog;

		if (isset($_POST['Blog'])) {
			$blog->name = $_POST['Blog']['name'];
			$blog->content = $_POST['Blog']['content'];

			if ($blog->save()) {
				$this->redirect('/blog/post/'.$blog->id);
			}
		}

		echo $this->render('create', array('model'=>$blog));
	}

	public function actionUpdate() {
		$crt = new MQuery;
		$crt->addWhere('id = :id');
		$crt->params = array(
			':id' => $_GET['id']
		);
		$blog = Blog::finder($crt, true);

		$blog->name = 'setupher';
		$blog->save();
	}

	public function actionDelete() {
		$crt = new MQuery;
		$crt->addWhere('id = :id');
		$crt->params = array(
			':id' => $_GET['id']
		);
		$blog = Blog::finder($crt, true);
		$blog->delete();
	}
}