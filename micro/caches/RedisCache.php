<?php

namespace Micro\caches;


use Micro\base\Exception;

class RedisCache implements Cache
{
    /** @var \Redis $driver driver redis */
    protected $driver;

    public function __construct($config = []) {
        if (!$this->check()) {
            throw new Exception('Redis not installed on system');
        }
        $this->driver = new \Redis;

        $result = false;
        try {
            if (isset($config['socket_type']) AND $config['socket_type']==='unix') {
                $result = $this->driver->connect($config['socket']);
            } else {
                $result = $this->driver->connect($config['host'], $config['port'], $config['duration']);
            }
        } catch (Exception $e) {
            die ( (string)$e );
        }

        if (!$result) {
            throw new Exception('Redis configuration failed');
        }
    }

    public function __destruct() {
        if ($this->driver) {
            $this->driver->close();
        }
    }

    public function check()
    {
        return extension_loaded('redis');
    }

    public function get($name)
    {
        return $this->driver->get($name);
    }

    public function set($name, $value, $duration = 0)
    {
        return ($duration) ? $this->driver->setex($name, $duration, $value) : $this->driver->set($name, $value);
    }

    public function delete($name)
    {
        return ($this->driver->delete($name) !== 1) ? FALSE : TRUE;
    }

    public function clean($name)
    {
        return $this->driver->flushDB();
    }

    public function info()
    {
        return $this->driver->info();
    }

    public function getMeta($id)
    {
        if ($value = $this->get($id)) {
            return [ 'expire' => time() + $this->driver->ttl($id), 'data' => $value ];
        }
        return FALSE;
    }

    public function increment($name, $offset = 1)
    {
        return $this->driver->incrBy($name, $offset);
    }

    public function decrement($name, $offset = 1)
    {
        return $this->driver->decrBy($name, $offset);
    }
} 