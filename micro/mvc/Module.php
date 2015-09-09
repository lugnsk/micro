<?php

namespace Micro\mvc;

use Micro\base\IContainer;

abstract class Module
{
    /** @var IContainer $container */
    public $container;


    /**
     * @param IContainer $container
     */
    public function __construct(IContainer $container)
    {
        $this->container = $container;

        $path = dirname(
                str_replace(['\\', 'App'], ['/', $container->kernel->getAppDir()], get_called_class())
            ) . '/config.php';

        if (file_exists($path)) {
            $container->load($path);
        }
    }
}
