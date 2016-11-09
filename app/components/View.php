<?php

namespace App\Components;

use Micro\Base\IContainer;
use Micro\Mvc\Views\PhpView;
use Micro\Web\UserInjector;

/**
 * Class View
 *
 * @package App\Components
 */
class View extends PhpView
{
    public $title = 'Micro';
    public $menu = ['<a href="/">Главная</a>', '<a href="/blog/post">Блог</a>'];
    public $user = [];

    /**
     * View constructor.
     */
    public function __construct()
    {
        parent::__construct();

        if (!(new UserInjector)->build()->isGuest()) {
            $this->user[] = '<a href="/profile">Профиль</a>';
            $this->user[] = ' (<a href="/logout">Выйти</a>)';
        } else {
            $this->user[] = '<a href="/login">Войти</a>';
            $this->user[] = '<a href="/register">Регистрация</a>';
        }
    }
} 
