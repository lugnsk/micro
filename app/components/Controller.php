<?php

namespace App\components;

use Micro\base\Controller AS BaseController;
use Micro\base\Registry;

class Controller extends BaseController
{
	public $title = 'Micro';
	public $layout = 'maket';

	public $menu = ['<a href="/">Главная</a>', '<a href="/blog/post">Блог</a>'];

	public function __construct() {
		parent::__construct();

		if (!Registry::get('user')->isGuest()) {
			$this->menu[] = '<a href="/profile">Профиль</a>';
			$this->menu[] = ' (<a href="/logout">Выйти</a>)';
		} else {
			$this->menu[] = '<a href="/login">Войти</a>';
			$this->menu[] = '<a href="/register">Регистрация</a>';
		}
	}
}