<?php

namespace App\components;

use Micro\mvc\controllers\ViewController as BaseController;

class Controller extends BaseController
{
    public function __construct()
    {
        $this->layout = 'maket';

        parent::__construct();
    }
}