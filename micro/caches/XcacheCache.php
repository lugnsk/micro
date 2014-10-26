<?php

namespace Micro\caches;


class XcacheCache implements Cache
{
    public function check()
    {
        return extension_loaded('xcache') ? TRUE : FALSE;
    }

    public function get($name)
    {
        return xcache_isset($name) ? xcache_get($name) : false;
    }

    public function set($name, $value, $duration=0)
    {
        return xcache_set($name, $value, $duration);
    }

    public function delete($name)
    {
        return xcache_unset($name);
    }

    public function clean()
    {
        for ($i = 0, $cnt = xcache_count(XC_TYPE_VAR); $i < $cnt; $i++) {
            if (xcache_clear_cache(XC_TYPE_VAR, $i) === false) {
                return false;
            }
        }
        return true;
    }

    public function info()
    {
        return xcache_count(XC_TYPE_VAR);
    }

    public function getMeta($id)
    {
        return FALSE;
    }

    public function increment($name, $offset = 1)
    {
        $val = $this->get($name) + $offset;
        return $this->set($name, $val);
    }

    public function decrement($name, $offset = 1)
    {
        $val = $this->get($name) - $offset;
        return $this->set($name, $val);
    }
} 