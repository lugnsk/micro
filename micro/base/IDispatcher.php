<?php

namespace Micro\base;

interface IDispatcher
{
    /**
     * Add listener on event
     *
     * @access public
     *
     * @param string $listener listener name
     * @param array $event ['Object', 'method']
     * @param int|null $prior priority
     *
     * @return void
     */
    public function addListener($listener, array $event = [], $prior = null);

    /**
     * Send signal to run event
     *
     * @access public
     *
     * @param string $listener listener name
     * @param array $params Signal parameters
     *
     * @return mixed
     */
    public function signal($listener, array $params = []);
}