<?php

namespace App;

use Micro\Micro;


/**
 * Class Application
 * @package App
 */
class Application extends Micro
{
    /**
     * @return string
     */
    public function getAppDir()
    {
        return __DIR__;
    }
}
