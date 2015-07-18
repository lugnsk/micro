<?php

namespace Micro\web;

interface IRouter
{
    /**
     * Parsing uri
     *
     * @access public
     *
     * @param string $uri current check URI
     * @param string $method current Request method
     *
     * @return string
     */
    public function parse($uri, $method = 'GET');
}