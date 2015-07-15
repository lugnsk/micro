<?php /** MicroRedisCache */

namespace Micro\caches;

use Micro\base\Exception;

/**
 * Class RedisCache
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage caches
 * @version 1.0
 * @since 1.0
 */
class RedisCache implements CacheInterface
{
    /** @var \Redis $driver driver redis */
    protected $driver;

    /**
     * Constructor
     *
     * @access public
     *
     * @param array $config config array
     *
     * @result void
     * @throws Exception
     */
    public function __construct(array $config = [])
    {
        if (!$this->check()) {
            throw new Exception('Redis not installed on system');
        }
        $this->driver = new \Redis;

        $result = false;
        try {
            if (!empty($config['socket_type']) AND $config['socket_type'] === 'unix') {
                $result = $this->driver->connect($config['socket']);
            } else {
                $result = $this->driver->connect($config['host'], $config['port'], $config['duration']);
            }
        } catch (Exception $e) {
            throw new Exception((string)$e);
        }

        if (!$result) {
            throw new Exception('Redis configuration failed');
        }
    }

    /**
     * Check driver
     *
     * @access public
     * @return mixed
     */
    public function check()
    {
        return extension_loaded('redis');
    }

    /**
     * Destructor
     *
     * @access public
     * @result void
     */
    public function __destruct()
    {
        if ($this->driver) {
            $this->driver->close();
        }
    }

    /**
     * Set value of element
     *
     * @access public
     *
     * @param string $name key name
     * @param mixed $value value
     * @param integer $duration time duration
     *
     * @return mixed
     */
    public function set($name, $value, $duration = 0)
    {
        return ($duration) ? $this->driver->setex($name, $duration, $value) : $this->driver->set($name, $value);
    }

    /**
     * Delete by key name
     *
     * @access public
     *
     * @param string $name key name
     *
     * @return mixed
     */
    public function delete($name)
    {
        return ($this->driver->delete($name) !== 1) ?: true;
    }

    /**
     * Clean all data from cache
     *
     * @access public
     * @return mixed
     */
    public function clean()
    {
        return $this->driver->flushDB();
    }

    /**
     * Summary info about cache
     *
     * @access public
     * @return mixed
     */
    public function info()
    {
        return $this->driver->info();
    }

    /**
     * Get meta-data of key id
     *
     * @access public
     *
     * @param string $id key id
     *
     * @return mixed
     */
    public function getMeta($id)
    {
        if ($value = $this->get($id)) {
            return ['expire' => time() + $this->driver->ttl($id), 'data' => $value];
        }

        return false;
    }

    /**
     * Get value by name
     *
     * @access public
     *
     * @param string $name key name
     *
     * @return mixed
     */
    public function get($name)
    {
        return $this->driver->get($name);
    }

    /**
     * Increment value
     *
     * @access public
     *
     * @param string $name key name
     * @param int $offset increment value
     *
     * @return mixed
     */
    public function increment($name, $offset = 1)
    {
        return $this->driver->incrBy($name, $offset);
    }

    /**
     * Decrement value
     *
     * @access public
     *
     * @param string $name key name
     * @param int $offset decrement value
     *
     * @return mixed
     */
    public function decrement($name, $offset = 1)
    {
        return $this->driver->decrBy($name, $offset);
    }
} 