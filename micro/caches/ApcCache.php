<?php /** MicroApcCache */

namespace Micro\caches;

use Micro\base\Exception;

/**
 * Class ApcCache
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage caches
 * @version 1.0
 * @since 1.0
 */
class ApcCache implements Cache
{
    /**
     * Constructor
     *
     * @access public
     * @param array $config config array
     * @result void
     * @throws Exception
     */
    public function __construct(array $config = [])
    {
        if (!$this->check()) {
            throw new Exception('APC cache not installed');
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
        if(extension_loaded('apc') && ini_get('apc.enabled')) {
            return true;
        } else {
            return false;
        }
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
        $values = apc_fetch($name);
        return is_array($values) ? $values : [];
    }

    /**
     * Set value of element
     *
     * @access public
     * @param string $name key name
     * @param mixed $value value
     * @param integer $duration time duration
     * @param boolean $new is new element?
     * @return mixed
     */
    public function set($name, $value, $duration = 300, $new = false)
    {
        if($new == true) {
            return apc_add($name, $value, $duration);
        } else {
            return apc_store($name, $value, $duration);
        }
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
        return apc_delete($name);
    }

    /**
     * Clean all data from cache
     *
     * @access public
     * @return mixed
     */
    public function clean()
    {
        if (extension_loaded('apcu')) {
            return apc_clear_cache();
        } else {
            return apc_clear_cache('user');
        }
    }

    /**
     * Summary info about cache
     *
     * @access public
     * @param mixed $type type
     * @return mixed
     */
    public function info($type = NULL)
    {
        return apc_cache_info($type);
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
        $success = false;

        $stored = apc_fetch($id, $success);
        if ($success === false OR count($stored) !== 3) {
            return false;
        }

        list($data, $time, $ttl) = $stored;
        return [ 'expire' => $time + $ttl, 'mtime' => $time, 'data' => unserialize($data) ];
    }

    /**
     * Increment value
     *
     * @access public
     * @param string $name key name
     * @param int $offset increment value
     * @return mixed
     */
    public function increment($name, $offset = 1)
    {
        return apc_inc($name, $offset);
    }

    /**
     * Decrement value
     *
     * @access public
     * @param string $name key name
     * @param int $offset decrement value
     * @return mixed
     */
    public function decrement($name, $offset = 1)
    {
        return apc_dec($name, $offset);
    }
} 