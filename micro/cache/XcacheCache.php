<?php /** MicroXcacheCache */

namespace Micro\cache;

use Micro\base\Exception;

/**
 * Class XcacheCache
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
class XcacheCache extends BaseCache
{
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

        if (!$this->check()) {
            throw new Exception($this->container, 'Extension XCache not installed');
        }
    }

    /**
     * @inheritdoc
     */
    public function check()
    {
        return extension_loaded('xcache') ? true : false;
    }

    /**
     * @inheritdoc
     */
    public function delete($name)
    {
        return xcache_unset($name);
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function info()
    {
        return xcache_count(XC_TYPE_VAR);
    }

    /**
     * @inheritdoc
     */
    public function getMeta($id)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function increment($name, $offset = 1)
    {
        $val = $this->get($name) + $offset;

        return $this->set($name, $val);
    }

    /**
     * @inheritdoc
     */
    public function get($name)
    {
        return xcache_isset($name) ? xcache_get($name) : false;
    }

    /**
     * @inheritdoc
     */
    public function set($name, $value, $duration = 0)
    {
        return xcache_set($name, $value, $duration);
    }

    /**
     * @inheritdoc
     */
    public function decrement($name, $offset = 1)
    {
        $val = $this->get($name) - $offset;

        return $this->set($name, $val);
    }
} 
