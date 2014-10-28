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
        'array',
        'apc',
        'file',
        'memcache',
        'memcached',
        'redis',
        'wincache',
        'xcache',
    ];
}