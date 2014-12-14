<?php

namespace App\widgets;

use App\components\View;
use Micro\base\Widget;

class MenubarWidget extends Widget
{
    public function init()
    {
    }

    public function run()
    {
        //echo $this->render('menubar');
        $v = new View;
        $v->view = 'menubar';
        $v->asWidget = true; //die(var_dump($v));
        return $v;
    }
}