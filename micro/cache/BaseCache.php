<?php

namespace Micro\cache;


use Micro\base\IContainer;

abstract class BaseCache implements ICache
{
    /** @var IContainer $container */
    protected $container;

    public function __construct(array $params = [])
    {
        $this->container = $params['container'];
    }
}