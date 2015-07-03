<?php

namespace App\modules\blog\widgets;

use App\components\View;
use Micro\mvc\Widget;

class TopblogsWidget extends Widget
{
    public function init()
    {
    }

    public function run()
    {
        $v = new View( $this->container );
        echo $v;
    }
}