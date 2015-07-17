<?php /** MicroCache */

namespace Micro\cache;

use Micro\base\Container;
use Micro\base\Exception;

/**
 * Cache class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage wrappers
 * @version 1.0
 * @since 1.0
 */
class Cache
{
    /** @var array $drivers Supported drivers */
    protected static $drivers = [
        'array' => '\\Micro\\cache\\ArrayCache',
        'apc' => '\\Micro\\cache\\ApcCache',
        'file' => '\\Micro\\cache\\FileCache',
        'memcache' => '\\Micro\\cache\\MemcacheCache',
        'memcached' => '\\Micro\\cache\\MemcacheCache',
        'redis' => '\\Micro\\cache\\RedisCache',
        'wincache' => '\\Micro\\cache\\WincacheCache',
        'xcache' => '\\Micro\\cache\\XcacheCache'
    ];
    /** @var array $servers Activated servers */
    protected $servers = [];
    /** @var Container $container Config container */
    protected $container;


    /**
     * Constructor is a initialize Caches
     *
     * @access public
     *
     * @param array $config Caching config
     *
     * @result void
     * @throws Exception
     */
    public function __construct(array $config = [])
    {
        $this->container = $config['container'];

        if (empty($config['servers'])) {
            throw new Exception($this->container, 'Caching not configured');
        }

        foreach ($config['servers'] AS $key => $server) {
            if (array_key_exists($server['driver'], array_keys(self::$drivers))) {
                $this->servers[$key] = new self::$drivers[$server['driver']] ($server);
            } else {
                throw new Exception($this->container, 'Cache driver `' . $server['driver'] . '` not found');
            }
        }
    }

    /**
     * Get cache server by name
     *
     * @access public
     *
     * @param string $driver server name
     *
     * @return mixed
     * @throws Exception
     */
    public function get($driver = null)
    {
        if (!$driver) {
            return $this->servers[0];
        }

        if (in_array($driver, $this->servers, true)) {
            return $this->servers[$driver];
        } else {
            throw new Exception($this->container, 'Cache `' . $driver . '` not found.');
        }
    }
}