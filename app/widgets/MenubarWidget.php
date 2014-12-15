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
        $v = new View;
        $v->path = get_class($this);
        $v->view = 'menubar';
        $v->asWidget = true;

        return $v;
    }
}