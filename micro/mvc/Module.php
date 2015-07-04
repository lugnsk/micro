<?php

namespace Micro\mvc;

use Micro\base\Registry;

abstract class Module
{
    public function __construct(Registry $container)
    {
        $path = dirname(
            str_replace(['\\','App'], ['/',$container->kernel->getAppDir()], get_called_class())
        ) . '/config.php';

        if (file_exists($path)) {
            $container->load($path);
        }
    }
}