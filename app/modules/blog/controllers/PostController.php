<?php

class PostController extends Controller
{
	public function actionIndex() {
		$crt = new MQuery;
		$crt->limit = 10;

		if (isset($_GET['page']) AND is_numeric($_GET['page'])) {
			$crt->offset = $_GET['page'] * $crt->limit;
		}

		echo $this->render('index', array('blogs'=>Blog::finder($crt)));
	}

	public function actionCreate() {
		$blog = new Blog;
		$blog->name = 'setuper';
		$blog->content = 'setup create has been modified.';
		die(var_dump($blog->save()));
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