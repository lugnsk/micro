<?php /** MicroCache */

namespace Micro\base;

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
        'array' => '\\Micro\\caches\\ArrayCache',
        'apc' => '\\Micro\\caches\\ApcCache',
        'file' => '\\Micro\\caches\\FileCache',
        'memcache' => '\\Micro\\caches\\MemcacheCache',
        'memcached' => '\\Micro\\caches\\MemcacheCache',
        'redis' => '\\Micro\\caches\\RedisCache',
        'wincache' => '\\Micro\\caches\\WincacheCache',
        'xcache' => '\\Micro\\caches\\XcacheCache'
    ];
    /** @var array $servers Activated servers */
    protected $servers = [];
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

        if (!empty($config['servers'])) {
            foreach ($config['servers'] AS $key => $server) {
                if (in_array($server['driver'], array_keys(self::$drivers), true)) {
                    $this->servers[$key] = new self::$drivers[$server['driver']] ($server);
                } else {
                    throw new Exception($this->container, 'Cache driver `' . $server['driver'] . '` not found');
                }
            }
        } else {
            throw new Exception($this->container, 'Caching not configured');
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