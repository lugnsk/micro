<?php

namespace App\Assets;

use Micro\Base\KernelInjector;
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
        if ((new KernelInjector)->build()->isDebug()) {
            $this->js[] = '/jquery.js';
        } else {
            $this->js[] = '/jquery.min.js';
        }

        $this->sourcePath = (new KernelInjector)->build()->getAppDir() . '/../vendor/components/jquery';

        parent::__construct($view);
    }
}
