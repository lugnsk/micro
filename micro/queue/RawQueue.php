<?php /** MicroRawQueue */

namespace Micro\queue;

/**
 * RawQueue class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage queue
 * @version 1.0
 * @since 1.0
 */
class RawQueue implements IQueue
{
    /**
     * Constructor Queues
     *
     * @access public
     *
     * @param array $params Configuration array
     *
     * @result void
     */
    public function __construct(array $params = [])
    {
        // TODO: Implement __construct() method.
    }

    /**
     * Test connection
     *
     * @access public
     *
     * @return bool
     */
    public function test()
    {
        return false;
    }

    /**
     * Sync message
     *
     * @access public
     *
     * @param $name
     * @param array $params
     * @return mixed
     */
    public function sync($name, array $params = [])
    {
        // TODO: Implement sync() method.
    }

    /**
     * Async message
     *
     * @access public
     *
     * @param $name
     * @param array $params
     * @return mixed
     */
    public function async($name, array $params = [])
    {
        // TODO: Implement async() method.
    }

    /**
     * Stream message
     *
     * @access public
     *
     * @param $name
     * @param array $params
     * @return mixed
     */
    public function stream($name, array $params = [])
    {
        // TODO: Implement stream() method.
    }
}
