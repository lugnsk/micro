<?php

namespace App\Components;

use Micro\Base\Container;
use Micro\Mvc\Controllers\ViewController as BaseController;

/**
 * Class Controller
 * @package App\Components
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
