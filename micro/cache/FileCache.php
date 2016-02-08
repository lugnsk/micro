<?php /** MicroFileCache */

namespace Micro\Cache;

use Micro\Base\Exception;
use Micro\File\FileHelper;

/**
 * Class FileCache
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Cache
 * @version 1.0
 * @since 1.0
 */
class FileCache extends BaseCache
{
    /** @var string $driver directory name */
    protected $driver;

    /**
     * Constructor
     *
     * @access pubic
     *
     * @param array $config config array
     *
     * @result void
     * @throws Exception
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $path = !empty($config['path']) ? $config['path'] : sys_get_temp_dir() . '/cache';
        if (!@mkdir($path, 0600) && !is_dir($path)) {
            throw new Exception('Can`not create/check access to directory: ' . $path);
        }
        $this->driver = $path;
    }

    /**
     * @inheritdoc
     */
    public function check()
    {
        return is_writable($this->driver) ? true : false;
    }

    /**
     * @inheritdoc
     */
    public function delete($name)
    {
        unlink($this->driver . '/' . $name);
    }

    /**
     * @inheritdoc
     */
    public function clean()
    {
        FileHelper::removeDir($this->driver);
    }

    /**
     * @inheritdoc
     */
    public function info()
    {
        return count(scandir($this->driver)) - 2;
    }

    /**
     * @inheritdoc
     */
    public function getMeta($id)
    {
        return filesize($this->driver . '/' . $id);
    }

    /**
     * @inheritdoc
     */
    public function increment($name, $offset = 1)
    {
        $this->set($name, ((int)$this->get($name) + $offset));
    }

    /**
     * @inheritdoc
     */
    public function set($name, $value)
    {
        return file_put_contents($this->driver . '/' . $name, $value);
    }

    /**
     * @inheritdoc
     */
    public function get($name)
    {
        return file_get_contents($this->driver . '/' . $name);
    }

    /**
     * @inheritdoc
     */
    public function decrement($name, $offset = 1)
    {
        $this->set($name, ((int)$this->get($name) - $offset));
    }
} 
