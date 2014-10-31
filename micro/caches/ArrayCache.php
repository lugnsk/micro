<?php /** MicroArrayCache */

namespace Micro\caches;

/**
 * Class ArrayCache
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
class ArrayCache implements Cache
{
    /** @var array $driver array as driver */
    protected $driver = [];

    /**
     * Constructor
     *
     * @access public
     * @param array $config array config
     * @result void
     */
    public function __construct($config=[])
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
        return TRUE;
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
        return isset($this->driver[$name]) ? $this->driver[$name] : FALSE;
    }

    /**
     * Set value of element
     *
     * @access public
     * @param string $name key name
     * @param mixed $value value
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
     * @param string $name key name
     * @return mixed
     */
    public function delete($name)
    {
        if (isset($this->driver[$name])) {
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
     * @param string $id key id
     * @return mixed
     */
    public function getMeta($id)
    {
        if (isset($this->driver[$id])) {
            return $this->get_type($this->driver[$id]);
        }
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
        $this->driver[$name] = $this->driver[$name] + $offset;
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
        $this->driver[$name] = $this->driver[$name] - $offset;
    }

    /**
     * Get type of var
     *
     * @access protected
     * @param mixed $var any object
     * @return string
     */
    protected function get_type($var)
    {
        if (is_object($var)) {
            return get_class($var);
        } elseif (is_null($var)) {
            return 'null';
        } elseif (is_string($var)) {
            return 'string';
        } elseif (is_array($var)) {
            return 'array';
        } elseif (is_int($var)) {
            return 'integer';
        } elseif (is_bool($var)) {
            return 'boolean';
        } elseif (is_float($var)) {
            return 'float';
        } elseif (is_resource($var)) {
            return 'resource';
        } else {
            return 'unknown';
        }
    }
}