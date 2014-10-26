<?php

namespace Micro\caches;


class FileCache implements Cache
{
    protected $driver;

    public function __construct($config = [])
    {
        $path = (isset($config['path'])) ? $config['path'] : sys_get_temp_dir() . '/cache';
        if (!is_dir($path)) {
            mkdir($path, 0600);
        }
        $this->driver = $path;
    }

    public function check()
    {
        return is_writable($this->driver) ? TRUE : FALSE;
    }

    public function get($name)
    {
        return file_get_contents($this->driver . '/' . $name);
    }

    public function set($name, $value)
    {
        return file_put_contents($this->driver . '/' . $name, $value);
    }

    public function delete($name)
    {
        unlink($this->driver . '/' . $name);
    }

    public function clean()
    {
        $this->unlinkRecursive($this->driver);
    }

    public function info()
    {
        return count(scandir($this->driver))-2;
    }

    public function getMeta($id)
    {
        return filesize($this->driver . '/' . $id);
    }

    public function increment($name, $offset = 1)
    {
        $this->set($name, ((integer)$this->get($name) + $offset) );
    }

    public function decrement($name, $offset = 1)
    {
        $this->set($name, ((integer)$this->get($name) - $offset) );
    }

    protected function unlinkRecursive($dir, $deleteRootToo=false)
    {
        if(!$dh = @opendir($dir))
        {
            return;
        }
        while (false !== ($obj = readdir($dh)))
        {
            if($obj == '.' || $obj == '..')
            {
                continue;
            }

            if (!@unlink($dir . '/' . $obj))
            {
                $this->unlinkRecursive($dir.'/'.$obj, true);
            }
        }

        closedir($dh);

        if ($deleteRootToo)
        {
            @rmdir($dir);
        }

        return;
    }
} 