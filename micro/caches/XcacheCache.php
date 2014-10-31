<?php /** MicroXcacheCache */

namespace Micro\caches;

use Micro\base\Exception;

/**
 * Class XcacheCache
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
class XcacheCache implements Cache
{
    /**
     * Constructor
     *
     * @access public
     * @param array $config config array
     * @result void
     * @throws Exception
     */
    public function __construct($config=[])
    {
        if (!$this->check()) {
            throw new Exception('Extension XCache not installed');
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
        return extension_loaded('xcache') ? TRUE : FALSE;
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
        return xcache_isset($name) ? xcache_get($name) : false;
    }

    /**
     * Set value of element
     *
     * @access public
     * @param string $name key name
     * @param mixed $value value
     * @param integer $duration time duration
     * @return mixed
     */
    public function set($name, $value, $duration=0)
    {
        return xcache_set($name, $value, $duration);
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
        return xcache_unset($name);
    }

    /**
     * Clean all data from cache
     *
     * @access public
     * @return mixed
     */
    public function clean()
    {
        for ($i = 0, $cnt = xcache_count(XC_TYPE_VAR); $i < $cnt; $i++) {
            if (xcache_clear_cache(XC_TYPE_VAR, $i) === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Summary info about cache
     *
     * @access public
     * @return mixed
     */
    public function info()
    {
        return xcache_count(XC_TYPE_VAR);
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
        return FALSE;
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
        $val = $this->get($name) + $offset;
        return $this->set($name, $val);
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
        $val = $this->get($name) - $offset;
        return $this->set($name, $val);
    }
} 