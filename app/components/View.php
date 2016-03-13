<?php

namespace App\Components;

use Micro\Base\IContainer;
use Micro\Mvc\Views\PhpView;

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
     * @param IContainer $container
     */
    public function __construct(IContainer $container)
    {
        parent::__construct($container);

        if (!$this->container->user->isGuest()) {
            $this->user[] = '<a href="/profile">Профиль</a>';
            $this->user[] = ' (<a href="/logout">Выйти</a>)';
        } else {
            $this->user[] = '<a href="/login">Войти</a>';
            $this->user[] = '<a href="/register">Регистрация</a>';
        }
    }
} 
