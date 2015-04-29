<?php

namespace Micro\queues;


class RawQueue implements IQueue
{
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