<?php

namespace Micro\caches;


class WincacheCache implements Cache
{
    public function check()
    {
        return (!extension_loaded('wincache')) ? TRUE : FALSE;
    }

    public function get($name)
    {
        $success = FALSE;
        $data = wincache_ucache_get($name, $success);

        return ($success) ? $data : FALSE;
    }

    public function set($name, $value, $duration=0)
    {
        return wincache_ucache_set($name, $value, $duration);
    }

    public function delete($name)
    {
        return wincache_ucache_delete($name);
    }

    public function clean()
    {
        return wincache_ucache_clear();
    }

    public function info()
    {
        return wincache_ucache_info(TRUE);
    }

    public function getMeta($id)
    {
        if ($stored = wincache_ucache_info(FALSE, $id))
        {
            $age = $stored['ucache_entries'][1]['age_seconds'];
            $ttl = $stored['ucache_entries'][1]['ttl_seconds'];
            $hitCount = $stored['ucache_entries'][1]['hitcount'];

            return [ 'expire' => $ttl - $age, 'hitcount' => $hitCount, 'age' => $age, 'ttl' => $ttl ];
        }
        return FALSE;
    }

    public function increment($name, $offset = 1)
    {
        $success = FALSE;
        $value = wincache_ucache_inc($name, $offset, $success);

        return ($success === TRUE) ? $value : FALSE;
    }

    public function decrement($name, $offset = 1)
    {
        $success = FALSE;
        $value = wincache_ucache_dec($name, $offset, $success);

        return ($success === TRUE) ? $value : FALSE;
    }
} 