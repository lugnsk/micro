<?php /** MicroCacheWrapper */

namespace Micro\wrappers;

/**
 * Cache class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage wrappers
 * @version 1.0
 * @since 1.0
 */
class Cache
{
    protected $drivers = [
        'array'     => '\\Micro\\caches\\ArrayCache',
        'apc'       => '\\Micro\\caches\\ApcCache',
        'file'      => '\\Micro\\caches\\FileCache',
        'memcache'  => '\\Micro\\caches\\MemcacheCache',
        'memcached' => '\\Micro\\caches\\MemcacheCache',
        'redis'     => '\\Micro\\caches\\RedisCache',
        'wincache'  => '\\Micro\\caches\\WincacheCache',
        'xcache'    => '\\Micro\\caches\\XcacheCache',
    ];
    protected $servers=[];

    function __construct($config=[])
    {
        foreach ($config['servers'] AS $server) {
            //
        }
    }
    public function get($driver=null)
    {
        return $this->servers[$driver];
    }
}