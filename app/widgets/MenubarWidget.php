<?php

namespace App\widgets;

use App\components\View;
use Micro\mvc\Widget;
use Micro\mvc\views\PhpView;

class MenubarWidget extends Widget
{
    public function init()
    {
    }

    public function run()
    {
        $view = new View($this->container);
        $view->path = get_class($this);
        $view->view = 'menubar';
        $view->asWidget = true;

        echo $view;
    }
}