<?php

namespace Micro\filter;

interface IFilter
{
    /**
     * PreFilter
     *
     * @access public
     *
     * @param array $params checked items and other params
     *
     * @return boolean
     */
    public function pre(array $params);

    /**
     * PostFilter
     *
     * @access public
     *
     * @param array $params checked items and other params
     *
     * @return mixed
     */
    public function post(array $params);
}