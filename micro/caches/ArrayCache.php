<?php

namespace Micro\caches;


class ArrayCache implements Cache
{
    protected $driver = [];

    protected function get_type($var)
    {
        if (is_object($var)) {
            return get_class($var);
        } elseif (is_null($var)) {
            return 'null';
        } elseif (is_string($var)) {
            return 'string';
        } elseif (is_array($var)) {
            return 'array';
        } elseif (is_int($var)) {
            return 'integer';
        } elseif (is_bool($var)) {
            return 'boolean';
        } elseif (is_float($var)) {
            return 'float';
        } elseif (is_resource($var)) {
            return 'resource';
        } else {
            return 'unknown';
        }
    }

    public function check()
    {
        return $this->info() ? TRUE : FALSE;
    }

    public function get($name)
    {
        return isset($this->driver[$name]) ? $this->driver[$name] : FALSE;
    }

    public function set($name, $value)
    {
        $this->driver[$name] = $value;
    }

    public function delete($name)
    {
        if (isset($this->driver[$name])) {
            unset($this->driver[$name]);
        }
    }

    public function clean($name)
    {
        $this->driver = [];
    }

    public function info()
    {
        return count($this->driver);
    }

    public function getMeta($id)
    {
        if (isset($this->driver[$id])) {
            return $this->get_type($this->driver[$id]);
        }
        return FALSE;
    }

    public function increment($name, $offset = 1)
    {
        $this->driver[$name] = $this->driver[$name] + $offset;
    }

    public function decrement($name, $offset = 1)
    {
        $this->driver[$name] = $this->driver[$name] - $offset;
    }
} 