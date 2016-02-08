<?php /** MicroRedisQueue */

namespace Micro\Queue;

/**
 * RedisQueue class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Queue
 * @version 1.0
 * @since 1.0
 */
class RedisQueue implements IQueue
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
     * @inheritdoc
     */
    public function test()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function sync($name, array $params = [])
    {
        return 'Hello, world!';
    }

    /**
     * @inheritdoc
     */
    public function async($name, array $params = [])
    {
        // TODO: Implement async() method.
    }

    /**
     * @inheritdoc
     */
    public function stream($name, array $params = [])
    {
        // TODO: Implement stream() method.
    }
}
