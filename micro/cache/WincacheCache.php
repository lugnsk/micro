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
class WincacheCache extends BaseCache
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
        parent::__construct($config);

        if (!$this->check()) {
            throw new Exception('Extension WinCache not installed');
        }
    }

    /**
     * @inheritdoc
     */
    public function check()
    {
        return (!extension_loaded('wincache')) ? true : false;
    }

    /**
     * @inheritdoc
     */
    public function get($name)
    {
        $success = false;
        $data = wincache_ucache_get($name, $success);

        return ($success) ? $data : false;
    }

    /**
     * @inheritdoc
     */
    public function set($name, $value, $duration = 0)
    {
        return wincache_ucache_set($name, $value, $duration);
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function info()
    {
        return wincache_ucache_info(true);
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function increment($name, $offset = 1)
    {
        $success = false;
        $value = wincache_ucache_inc($name, $offset, $success);

        return ($success === true) ? $value : false;
    }

    /**
     * @inheritdoc
     */
    public function decrement($name, $offset = 1)
    {
        $success = false;
        $value = wincache_ucache_dec($name, $offset, $success);

        return ($success === true) ? $value : false;
    }
} 
