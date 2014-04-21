<?php

class Controller extends MController
{
	public $title = 'Micro';
	public $layout = 'maket';

	public $menu = array('<a href="/">Главная</a>', '<a href="/blog/post">Блог</a>');

	public function __construct() {
		parent::__construct();

		if (isset($_SESSION['UserID']) AND !empty($_SESSION['UserID'])) {
			$this->menu[] = '<a href="/profile">Профиль</a>';
			$this->menu[] = ' (<a href="/logout">Выйти</a>)';
		} else {
			$this->menu[] = '<a href="/login">Войти</a>';
			$this->menu[] = '<a href="/register">Регистрация</a>';
		}
	}
}