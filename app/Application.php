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

    /**
     * @return string
     */
    protected function getConfig()
    {
        return $this->getAppDir() . '/configs/index.php';
    }
}
