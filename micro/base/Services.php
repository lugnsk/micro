<?php

namespace Micro\base;

class Services
{
    protected $servers = [];
    protected $routes = [];

    public function __construct( array $params = [] )
    {
        $this->servers = !empty($params['servers']) ? $params['servers'] : [];
        $this->routes  = !empty($params['routes'])  ? $params['routes']  : [];
    }

    public function send($type)
    {
        switch($type) {
            case 'sync':
            case 'async':
            case 'stream':
                break;
        }
    }
}