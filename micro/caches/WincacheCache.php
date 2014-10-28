<?php /** MicroWincacheCache */

namespace Micro\caches;

/**
 * Class WincacheCache
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage caches
 * @version 1.0
 * @since 1.0
 */
class WincacheCache implements Cache
{
    /**
     * Check driver
     *
     * @access public
     * @return mixed
     */
    public function check()
    {
        return (!extension_loaded('wincache')) ? TRUE : FALSE;
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
        $success = FALSE;
        $data = wincache_ucache_get($name, $success);

        return ($success) ? $data : FALSE;
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
        return wincache_ucache_set($name, $value, $duration);
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
        return wincache_ucache_delete($name);
    }

    /**
     * Clean all data from cache
     *
     * @access public
     * @return mixed
     */
    public function clean()
    {
        return wincache_ucache_clear();
    }

    /**
     * Summary info about cache
     *
     * @access public
     * @return mixed
     */
    public function info()
    {
        return wincache_ucache_info(TRUE);
    }

    public function getMeta($id)
    {
        if ($stored = wincache_ucache_info(FALSE, $id))
        {
            $age = $stored['ucache_entries'][1]['age_seconds'];
            $ttl = $stored['ucache_entries'][1]['ttl_seconds'];
            $hitCount = $stored['ucache_entries'][1]['hitcount'];

            return [ 'expire' => $ttl - $age, 'hitcount' => $hitCount, 'age' => $age, 'ttl' => $ttl ];
        }
        return FALSE;
    }

    public function increment($name, $offset = 1)
    {
        $success = FALSE;
        $value = wincache_ucache_inc($name, $offset, $success);

        return ($success === TRUE) ? $value : FALSE;
    }

    public function decrement($name, $offset = 1)
    {
        $success = FALSE;
        $value = wincache_ucache_dec($name, $offset, $success);

        return ($success === TRUE) ? $value : FALSE;
    }
} 