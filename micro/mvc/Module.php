<?php

namespace Micro\mvc;

use Micro\base\Container;

abstract class Module
{
    public function __construct(Container $container)
    {
        $path = dirname(
                str_replace(['\\', 'App'], ['/', $container->kernel->getAppDir()], get_called_class())
            ) . '/config.php';

        if (file_exists($path)) {
            $container->load($path);
        }
    }
}