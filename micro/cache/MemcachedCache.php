<?php /** MicroMemcachedCache */

namespace Micro\cache;

use Micro\base\Exception;

/**
 * Class MemcachedCache
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage cache
 * @version 1.0
 * @since 1.0
 */
class MemcachedCache extends BaseCache
{
    /** @var \Memcache|\Memcached $driver driver memcache(d) */
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

        if (!$this->check() OR empty($config['type'])) {
            throw new Exception($this->container, 'Memcache(d) not installed or not select type');
        }

        switch (strtolower($config['type'])) {
            case 'memcached': {
                $this->driver = new \Memcached;
                break;
            }
            case 'memcache': {
                $this->driver = new \Memcache;
                break;
            }
            default: {
                throw new Exception($this->container, 'Selected type not valid in the driver');
            }
        }

        if (!empty($config['servers'])) {
            $this->driver->addServers($config['servers']);
        } elseif ($config['server']) {
            $conf = $config['server'];
            $server = [
                'hostname' => (!empty($conf['hostname']) ? $conf['hostname'] : '127.0.0.1'),
                'port' => (!empty($conf['port']) ? $conf['port'] : 11211),
                'weight' => (!empty($conf['weight']) ? $conf['weight'] : 1)
            ];

            if (get_class($this->driver) === 'Memcached') {
                $this->driver->addServer($server['hostname'], $server['port'], $server['weight']);
            } else {
                $this->driver->addServer($server['hostname'], $server['port'], true, $server['weight']);
            }
        } else {
            throw new Exception($this->container, 'Server(s) not configured');
        }
    }

    /**
     * @inheritdoc
     */
    public function check()
    {
        return (!extension_loaded('memcached') && !extension_loaded('memcache')) ?: true;
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
    public function get($name)
    {
        $data = $this->driver->get($name);

        return is_array($data) ? $data[0] : $data;
    }

    /**
     * @inheritdoc
     */
    public function set($name, $value, $duration = 0)
    {
        switch (get_class($this->driver)) {
            case 'Memcached': {
                return $this->driver->set($name, $value, $duration);
                break;
            }
            case 'Memcache': {
                return $this->driver->set($name, $value, 0, $duration);
                break;
            }
            default: {
                return false;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function delete($name)
    {
        return $this->driver->delete($name);
    }

    /**
     * @inheritdoc
     */
    public function clean()
    {
        return $this->driver->flush();
    }

    /**
     * @inheritdoc
     */
    public function info()
    {
        return $this->driver->getStats();
    }

    /**
     * @inheritdoc
     */
    public function getMeta($id)
    {
        $stored = $this->driver->get($id);
        if (count($stored) !== 3) {
            return false;
        }
        list($data, $time, $ttl) = $stored;

        return ['expire' => $time + $ttl, 'mtime' => $time, 'data' => $data];
    }

    /**
     * @inheritdoc
     */
    public function increment($name, $offset = 1)
    {
        return $this->driver->increment($name, $offset);
    }

    /**
     * @inheritdoc
     */
    public function decrement($name, $offset = 1)
    {
        return $this->driver->decrement($name, $offset);
    }
}
