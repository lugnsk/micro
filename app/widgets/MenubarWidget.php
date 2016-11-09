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
    public $links = [];

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
        $view = new View();
        $view->addParameter('links', $this->links);
        $view->view = 'menubar';
        $view->asWidget = true;

        return $view;
    }
}
