<?php

namespace Micro\caches;

interface Cache
{
    public function check();

    public function get($name);

    public function set($name, $value);

    public function delete($name);

    public function clean();

    public function info();

    public function getMeta($id);

    public function increment($name, $offset = 1);

    public function decrement($name, $offset = 1);
}