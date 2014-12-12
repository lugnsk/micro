<?php
/**
 * Created by PhpStorm.
 * User: casper
 * Date: 13.12.14
 * Time: 0:32
 */

namespace App\components;


use Micro\mvc\views\PhpView;
use Micro\base\Registry;

class View extends PhpView
{
    public $title = 'Micro';
    public $menu = ['<a href="/">Главная</a>', '<a href="/blog/post">Блог</a>'];

    public function __construct()
    {

        if (!Registry::get('user')->isGuest()) {
            $this->menu[] = '<a href="/profile">Профиль</a>';
            $this->menu[] = ' (<a href="/logout">Выйти</a>)';
        } else {
            $this->menu[] = '<a href="/login">Войти</a>';
            $this->menu[] = '<a href="/register">Регистрация</a>';
        }
    }
} 