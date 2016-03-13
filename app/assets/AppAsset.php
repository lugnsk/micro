<?php

namespace App\Assets;

use Micro\Mvc\Views\IView;
use Micro\Web\Asset;


/**
 * Class AppAsset
 * @package App\Assets
 */
class AppAsset extends Asset
{
    public static $name = 'ApplicationJSS';
    public static $version = '0';


    /**
     * @param IView $view
     * @throws \Micro\base\Exception
     */
    public function __construct(IView $view)
    {
        $this->css[] = '/main.css';

        // required
        $this->required = [
            '\App\Assets\JqueryAsset',
            '\App\Assets\BootstrapAsset'
        ];

        $this->sourcePath = __DIR__ . '/app';

        parent::__construct($view);
    }
}
