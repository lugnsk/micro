<?php

namespace App\widgets;

use App\components\View;
use Micro\mvc\Widget;

class MenubarWidget extends Widget
{
    public function init()
    {
    }

    public function run()
    {
        $view = new View;
        $view->path = get_class($this);
        $view->view = 'menubar';
        $view->asWidget = true;

        echo $view;
    }
}