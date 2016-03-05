<?php

namespace App\Modules\Blog\Widgets;

use App\Components\View;
use Micro\Mvc\Widget;

/**
 * Class TopblogsWidget
 * @package App\Modules\Blog\Widgets
 */
class TopblogsWidget extends Widget
{
    /**
     *
     */
    public function init()
    {
    }

    /**
     *
     */
    public function run()
    {
        $v = new View($this->container);
        echo $v;
    }
}
