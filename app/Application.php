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
    protected function getConfig()
    {
        return __DIR__ . 'configs/index.php';
    }
}
