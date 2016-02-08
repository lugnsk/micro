<?php /** MicroRedisCache */

namespace Micro\Cache;

use Micro\Base\Exception;

/**
 * Class RedisCache
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
class RedisCache extends BaseCache
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
        parent::__construct($config);

        if (!$this->check()) {
            throw new Exception('Redis not installed on system');
        }
        $this->driver = new \Redis;

        try {
            if (!empty($config['socket_type']) && $config['socket_type'] === 'unix') {
                $result = $this->driver->connect($config['socket']);
            } else {
                $result = $this->driver->connect($config['host'], $config['port'], $config['duration']);
            }
        } catch (\RedisException $e) {
            throw new Exception((string)$e);
        }

        if (!$result) {
            throw new Exception('Redis configuration failed');
        }
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function set($name, $value, $duration = 0)
    {
        return ($duration) ? $this->driver->setex($name, $duration, $value) : $this->driver->set($name, $value);
    }

    /**
     * @inheritdoc
     */
    public function delete($name)
    {
        return ($this->driver->delete($name) !== 1) ?: true;
    }

    /**
     * @inheritdoc
     */
    public function clean()
    {
        return $this->driver->flushDB();
    }

    /**
     * @inheritdoc
     */
    public function info()
    {
        return $this->driver->info();
    }

    /**
     * @inheritdoc
     */
    public function getMeta($id)
    {
        if ($value = $this->get($id)) {
            return ['expire' => time() + $this->driver->ttl($id), 'data' => $value];
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function get($name)
    {
        return $this->driver->get($name);
    }

    /**
     * @inheritdoc
     */
    public function increment($name, $offset = 1)
    {
        return $this->driver->incrBy($name, $offset);
    }

    /**
     * @inheritdoc
     */
    public function decrement($name, $offset = 1)
    {
        return $this->driver->decrBy($name, $offset);
    }
} 
