<?php

namespace App\modules\blog\widgets;

use App\components\View;
use Micro\base\Widget;

class TopblogsWidget extends Widget
{
    public function init()
    {
    }

    public function run()
    {
        //echo $this->render('topblogs');
        $v = new View;
        return $v;
    }
}