<?php /** MicroWincacheCache */

namespace Micro\cache;

use Micro\base\Exception;

/**
 * Class WincacheCache
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
class WincacheCache implements CacheInterface
{
    /**
     * Constructor
     *
     * @access public
     *
     * @param array $config array config
     *
     * @result void
     * @throws Exception
     */
    public function __construct(array $config = [])
    {
        if (!$this->check()) {
            throw new Exception('Extension WinCache not installed');
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
        return (!extension_loaded('wincache')) ? true : false;
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
        $success = false;
        $data = wincache_ucache_get($name, $success);

        return ($success) ? $data : false;
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
        return wincache_ucache_set($name, $value, $duration);
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
        return wincache_ucache_info(true);
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
        if ($stored = wincache_ucache_info(false, $id)) {
            $age = $stored['ucache_entries'][1]['age_seconds'];
            $ttl = $stored['ucache_entries'][1]['ttl_seconds'];
            $hitCount = $stored['ucache_entries'][1]['hitcount'];

            return ['expire' => $ttl - $age, 'hitcount' => $hitCount, 'age' => $age, 'ttl' => $ttl];
        }

        return false;
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
        $success = false;
        $value = wincache_ucache_inc($name, $offset, $success);

        return ($success === true) ? $value : false;
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
        $success = false;
        $value = wincache_ucache_dec($name, $offset, $success);

        return ($success === true) ? $value : false;
    }
} 