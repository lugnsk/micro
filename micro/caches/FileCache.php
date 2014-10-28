<?php /** MicroFileCache */

namespace Micro\caches;

/**
 * Class FileCache
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage caches
 * @version 1.0
 * @since 1.0
 */
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

    /**
     * Check driver
     *
     * @access public
     * @return mixed
     */
    public function check()
    {
        return is_writable($this->driver) ? TRUE : FALSE;
    }

    /**
     * Get value by name
     *
     * @access public
     * @param string $name key name
     * @return mixed
     */
    public function get($name)
    {
        return file_get_contents($this->driver . '/' . $name);
    }

    /**
     * Set value of element
     *
     * @access public
     * @param string $name key name
     * @param mixed $value value
     * @return mixed
     */
    public function set($name, $value)
    {
        return file_put_contents($this->driver . '/' . $name, $value);
    }

    /**
     * Delete by key name
     *
     * @access public
     * @param string $name key name
     * @return mixed
     */
    public function delete($name)
    {
        unlink($this->driver . '/' . $name);
    }

    /**
     * Clean all data from cache
     *
     * @access public
     * @return mixed
     */
    public function clean()
    {
        $this->unlinkRecursive($this->driver);
    }

    /**
     * Summary info about cache
     *
     * @access public
     * @return mixed
     */
    public function info()
    {
        return count(scandir($this->driver))-2;
    }

    /**
     * Get meta-data of key id
     *
     * @access public
     * @param string $id key id
     * @return mixed
     */
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