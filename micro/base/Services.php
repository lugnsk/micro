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

    public function send($route, array $data, $type, $retry)
    {
        switch($type) {
            case 'sync': {
                return $this->getBroker($route)->sync($route, $data, $retry);
                break;
            }
            case 'async': {
                return $this->getBroker($route)->async($route, $data, $retry);
                break;
            }
            case 'stream': {
                return $this->getBroker($route)->stream($route, $data, $retry);
                break;
            }
            default: {
                throw new Exception('Service type `' . $type . '` wrong name.');
            }
        }
    }

    /**
     * @param string $uri
     *
     * @return \Micro\queues\IQueue
     */
    private function getBroker($uri)
    {
        $broker = new \Micro\queues\RedisQueue; // @TODO: Replace me
        return $broker;
    }
}