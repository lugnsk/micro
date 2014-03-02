<?php

class PostController extends Controller
{
	public function actionList() {
		$crt = new MQuery;
		//$crt->addSearch('name', 'hello');
		$blogs = Blog::finder($crt);

		echo $this->render('index', array('blogs'=>$blogs));
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