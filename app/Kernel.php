<?php

namespace App;


/**
 * Class Application
 * @package App
 */
class Kernel extends \Micro\base\Kernel
{
    /**
     * @return string
     */
    public function getAppDir()
    {
        return __DIR__;
    }
}
