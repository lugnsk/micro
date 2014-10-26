<?php

namespace Micro\caches;


use Micro\base\Exception;

class MemcachedCache implements Cache
{
    /** @var \Memcache|\Memcached $driver driver memcache(d) */
    protected $driver;

    public function __construct($config = [])
    {
        if (!$this->check() OR !isset($config['type'])) {
            throw new Exception('Memcache(d) not installed or not select type');
        }

        switch ($config['type']) {
            case 'Memcached': {
                $this->driver = new \Memcached;
                break;
            }
            case 'Memcache': {
                $this->driver = new \Memcache;
                break;
            }
            default: {
                throw new Exception('Selected type not valid in the driver');
            }
        }

        if (isset($config['servers'])) {
            $this->driver->addServers($config['servers']);
        } elseif ($config['server']) {
            $conf = $config['server'];
            $server = [
                'hostname'  => (isset($conf['hostname']) ? $conf['hostname'] : '127.0.0.1'),
                'port'      => (isset($conf['port']) ? $conf['port'] : 11211),
                'weight'    => (isset($conf['weight']) ? $conf['weight'] : 1)
            ];

            if (get_class($this->driver) === 'Memcached') {
                $this->driver->addServer( $server['hostname'], $server['port'], $server['weight'] );
            } else {
                $this->driver->addServer( $server['hostname'], $server['port'], TRUE, $server['weight'] );
            }
        } else {
            throw new Exception('Server(s) not configured');
        }
    }

    public function __destruct()
    {
        if ($this->driver) {
            $this->driver->close();
        }
    }

    public function check()
    {
        return ( ! extension_loaded('memcached') && ! extension_loaded('memcache')) ? FALSE : TRUE;
    }

    public function get($name)
    {
        $data = $this->driver->get($name);
        return is_array($data) ? $data[0] : $data;
    }

    public function set($name, $value, $duration = 0)
    {
        switch (get_class($this->driver)) {
            case 'Memcached': {
                return $this->driver->set($name, $value, $duration);
                break;
            }
            case 'Memcache': {
                return $this->driver->set($name, $value, 0, $duration);
                break;
            }
            default: {
                return FALSE;
            }
        }
    }

    public function delete($name)
    {
        return $this->driver->delete($name);
    }

    public function clean()
    {
        return $this->driver->flush();
    }

    public function info()
    {
        return $this->driver->getStats();
    }

    public function getMeta($id)
    {
        $stored = $this->driver->get($id);
        if (count($stored) !== 3) {
            return false;
        }
        list($data, $time, $ttl) = $stored;

        return ['expire' => $time + $ttl, 'mtime' => $time, 'data' => $data];
    }

    public function increment($name, $offset = 1)
    {
        return $this->driver->increment($name, $offset);
    }

    public function decrement($name, $offset = 1)
    {
        return $this->driver->decrement($name, $offset);
    }
} 