<?php

namespace App\components;

use Micro\base\Registry;
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
     * @param Registry $registry
     * @param string $modules
     *
     * @result void
     */
    public function __construct(Registry $registry, $modules = '')
    {
        $this->layout = 'maket';

        parent::__construct($registry, $modules);
    }
}