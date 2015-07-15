<?php

namespace App\components;

use Micro\base\Container;
use Micro\mvc\controllers\ViewController as BaseController;

/**
 * Class Controller
 * @package App\components
 */
class Controller extends BaseController
{
    /**
     * Constructor controller
     *
     * @access public
     *
     * @param Container $container
     * @param string $modules
     *
     * @result void
     */
    public function __construct(Container $container, $modules = '')
    {
        $this->layout = 'maket';

        parent::__construct($container, $modules);
    }
}