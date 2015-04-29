<?php

namespace Micro\queues;


interface IQueue
{
    public function __construct( array $params = [] );
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
    public function sync( $name, array $params = []);

    /**
     * Async message
     *
     * @access public
     *
     * @param $name
     * @param array $params
     * @return mixed
     */
    public function async( $name, array $params = []);

    /**
     * Stream message
     *
     * @access public
     *
     * @param $name
     * @param array $params
     * @return mixed
     */
    public function stream( $name, array $params = []);
}