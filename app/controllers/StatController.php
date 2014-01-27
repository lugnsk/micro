<?php

class StatController extends Controller
{
	public function actionIndex() {
		echo $this->render('index', array(
			'emails'=>$this->getDoublesMails(),
			'norders'=>$this->getNoOrders(),
			'moreorders'=>$this->getManyOrders()
		));
	}

	private function getDoublesMails() {
		$query = new MQuery;
		$query->select = 'm.*, COUNT(email) AS dub';
		$query->group = 'm.email';
		$query->having = 'dub >= 2';
		return User::finder($query);
	}

	private function getNoOrders() {
		$query = new MQuery;
		$query->distinct = true;
		$query->table = 'orders';
		$query->select = 'user_id';

		$queryn = new MQuery;
		$queryn->addNotIn('id', $query->getQuery());
		return User::finder($queryn);
	}

	private function getManyOrders() {
		$query = new MQuery;
		$query->select = 'm.*, COUNT(t.user_id) AS dub';
		$query->addJoin('orders t', 't.user_id=m.id');
		$query->group = 't.user_id';
		$query->having = 'dub > 2';
		return User::finder($query);
	}
}