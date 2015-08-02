<?php

namespace Micro\mvc\controllers;

use Micro\web\IResponse;

interface IController
{
    /**
     * Apply filters
     *
     * @access public
     *
     * @param string $action current action name
     * @param bool $isPre is pre or post
     * @param array $filters defined filters
     * @param string $data data to parse
     *
     * @return null|string
     * @throws Exception error on filter
     */
    public function applyFilters($action, $isPre = true, array $filters = [], $data = null);

    /**
     * Master action
     *
     * @access public
     *
     * @param string $name Called action name
     *
     * @return string|IResponse
     */
    public function action($name = 'index');

}
