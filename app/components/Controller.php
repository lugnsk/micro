<?php

namespace App\components;

use Micro\base\MController;
use Micro\base\MRegistry;

class Controller extends MController
{
	public $title = 'Micro';
	public $layout = 'maket';

	public $menu = array('<a href="/">Главная</a>', '<a href="/blog/post">Блог</a>');

	public function __construct() {
		parent::__construct();

		if (!MRegistry::get('user')->isGuest()) {
			$this->menu[] = '<a href="/profile">Профиль</a>';
			$this->menu[] = ' (<a href="/logout">Выйти</a>)';
		} else {
			$this->menu[] = '<a href="/login">Войти</a>';
			$this->menu[] = '<a href="/register">Регистрация</a>';
		}
	}
}