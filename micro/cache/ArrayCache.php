<?php /** MicroArrayCache */

namespace Micro\Cache;

use Micro\File\Type;

/**
 * Class ArrayCache
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Cache
 * @version 1.0
 * @since 1.0
 */
class ArrayCache extends BaseCache
{
    /** @var array $driver array as driver */
    protected $driver = [];

    /**
     * @inheritdoc
     */
    public function check()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function get($name)
    {
        return !empty($this->driver[$name]) ? $this->driver[$name] : false;
    }

    /**
     * @inheritdoc
     */
    public function set($name, $value)
    {
        $this->driver[$name] = $value;
    }

    /**
     * 2@inheritdoc
     */
    public function delete($name)
    {
        if (!empty($this->driver[$name])) {
            unset($this->driver[$name]);
        }
    }

    /**
     * @inheritdoc
     */
    public function clean()
    {
        $this->driver = [];
    }

    /**
     * @inheritdoc
     */
    public function info()
    {
        return count($this->driver);
    }

    /**
     * @inheritdoc
     */
    public function getMeta($id)
    {
        if (!empty($this->driver[$id])) {
            return Type::getType($this->driver[$id]);
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function increment($name, $offset = 1)
    {
        $this->driver[$name] += $offset;
    }

    /**
     * @inheritdoc
     */
    public function decrement($name, $offset = 1)
    {
        $this->driver[$name] -= $offset;
    }
}
