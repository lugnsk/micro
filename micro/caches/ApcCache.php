<?php

namespace Micro\caches;


use Micro\base\Exception;

class ApcCache implements Cache
{
    public function __construct($config = [])
    {
        if (!$this->check()) {
            throw new Exception('APC cache not installed');
        }
    }

    public function check()
    {
        if(extension_loaded('apc') && ini_get('apc.enabled')) {
            return true;
        } else {
            return false;
        }
    }

    public function get($name)
    {
        $values = apc_fetch($name);
        return is_array($values) ? $values : [];
    }

    public function set($name, $value, $duration = 300, $new = false)
    {
        if($new == true) {
            return apc_add($name, $value, $duration);
        } else {
            return apc_store($name, $value, $duration);
        }
    }

    public function delete($name)
    {
        return apc_delete($name);
    }

    public function clean()
    {
        if (extension_loaded('apcu')) {
            return apc_clear_cache();
        } else {
            return apc_clear_cache('user');
        }
    }

    public function info($type = NULL)
    {
        return apc_cache_info($type);
    }

    public function getMeta($id)
    {
        $success = false;

        $stored = apc_fetch($id, $success);
        if ($success === false OR count($stored) !== 3) {
            return false;
        }

        list($data, $time, $ttl) = $stored;
        return [ 'expire' => $time + $ttl, 'mtime' => $time, 'data' => unserialize($data) ];
    }

    public function increment($name, $offset = 1)
    {
        return apc_inc($name, $offset);
    }

    public function decrement($name, $offset = 1)
    {
        return apc_dec($name, $offset);
    }
} 