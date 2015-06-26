<?php

namespace App\components;

use Micro\mvc\controllers\ViewController as BaseController;
use Micro\web\Request;
use Micro\base\Registry;

class Controller extends BaseController
{
    public function __construct( Registry $registry, $modules='' )
    {
        $this->layout = 'maket';

        parent::__construct($registry, $modules);
    }
}