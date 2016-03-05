<?php

namespace App\Widgets;

use App\Components\View;
use Micro\Mvc\Widget;

/**
 * Class MenubarWidget
 * @package App\Widgets
 */
class MenubarWidget extends Widget
{
    /**
     *
     */
    public function init()
    {
    }

    /**
     * @return View
     */
    public function run()
    {
        $view = new View($this->container);
        //$view->addParameter('menu', $this->links);
        $view->view = 'menubar';
        $view->asWidget = true;

        return $view;
    }
}
