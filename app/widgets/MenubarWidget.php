<?php

namespace App\Widgets;

use App\Components\View;
use Micro\Mvc\Widget;

class MenubarWidget extends Widget
{
    public function init()
    {
    }

    public function run()
    {
        $view = new View($this->container);
        //$view->addParameter('menu', $this->links);
        $view->view = 'menubar';
        $view->asWidget = true;

        return $view;
    }
}
