<?php

namespace App\Assets;

use Micro\Mvc\Views\IView;
use Micro\Web\Asset;

/**
 * Class JqueryAsset
 * @package App\Assets
 */
class JqueryAsset extends Asset
{
    public static $name = 'JQuery';
    public static $version = '2.2.1';

    /**
     * @param IView $view
     * @throws \Micro\Base\Exception
     */
    public function __construct(IView $view)
    {
        if ($view->container->kernel->isDebug()) {
            $this->js[] = '/jquery.js';
        } else {
            $this->js[] = '/jquery.min.js';
        }

        $this->sourcePath = $view->container->kernel->getAppDir() . '/../vendor/components/jquery';

        parent::__construct($view);
    }
}
