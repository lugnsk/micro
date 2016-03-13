<?php

namespace App\Assets;

use Micro\Mvc\Views\IView;
use Micro\Web\Asset;

/**
 * Class BootstrapAsset
 * @package App\Assets
 */
class BootstrapAsset extends Asset
{
    public static $name = 'Bootstrap';
    public static $version = '3.3.6';

    /**
     * @param IView $view
     * @throws \Micro\Base\Exception
     */
    public function __construct(IView $view)
    {
        if ($view->container->kernel->isDebug()) {
            $this->js[] = '/js/bootstrap.js';
            $this->css[] = '/css/bootstrap.css';
        } else {
            $this->js[] = '/js/bootstrap.min.js';
            $this->css[] = '/css/bootstrap.min.css';
        }

        $this->sourcePath = $view->container->kernel->getAppDir() . '/../vendor/twbs/bootstrap/dist';

        parent::__construct($view);
    }
}
