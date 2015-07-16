<?php

namespace Micro\queues;

/**
 * IQueue interface file.
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
interface IQueue
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
    public function __construct(array $params = []);

    /**
     * Test connection
     *
     * @access public
     *
     * @return bool
     */
    public function test();

    /**
     * Sync message
     *
     * @access public
     *
     * @param $name
     * @param array $params
     * @return mixed
     */
    public function sync($name, array $params = []);

    /**
     * Async message
     *
     * @access public
     *
     * @param $name
     * @param array $params
     * @return mixed
     */
    public function async($name, array $params = []);

    /**
     * Stream message
     *
     * @access public
     *
     * @param $name
     * @param array $params
     * @return mixed
     */
    public function stream($name, array $params = []);
}
