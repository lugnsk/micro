<?php /** MicroArrayCache */

namespace Micro\cache;

use Micro\base\Type;

/**
 * Class ArrayCache
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
class ArrayCache implements CacheInterface
{
    /** @var array $driver array as driver */
    protected $driver = [];

    /**
     * Constructor
     *
     * @access public
     *
     * @param array $config array config
     *
     * @result void
     */
    public function __construct(array $config = [])
    {
    }

    /**
     * Check driver
     *
     * @access public
     * @return mixed
     */
    public function check()
    {
        return true;
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
        return !empty($this->driver[$name]) ? $this->driver[$name] : false;
    }

    /**
     * Set value of element
     *
     * @access public
     *
     * @param string $name key name
     * @param mixed $value value
     *
     * @return mixed
     */
    public function set($name, $value)
    {
        $this->driver[$name] = $value;
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
        if (!empty($this->driver[$name])) {
            unset($this->driver[$name]);
        }
    }

    /**
     * Clean all data from cache
     *
     * @access public
     * @return mixed
     */
    public function clean()
    {
        $this->driver = [];
    }

    /**
     * Summary info about cache
     *
     * @access public
     * @return mixed
     */
    public function info()
    {
        return count($this->driver);
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
        if (!empty($this->driver[$id])) {
            return Type::getType($this->driver[$id]);
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
        $this->driver[$name] += $offset;
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
        $this->driver[$name] -= $offset;
    }
}