<?php

namespace Micro\web;

interface ICookie
{
    /**
     * Constructor of object
     *
     * @access public
     *
     * @param array $params Configuration array
     *
     * @result void
     */
    public function __construct(array $params);

    /**
     * Get cookie
     *
     * @access public
     *
     * @param string $name cookie name
     *
     * @return mixed|bool
     */
    public function get($name);

    /**
     * Get all cookies
     *
     * @access public
     * @return mixed
     */
    public function getAll();

    /**
     * Delete cookie
     *
     * @access public
     *
     * @param string $name cookie name
     *
     * @return bool
     */
    public function del($name);

    /**
     * Exists cookie
     *
     * @access public
     *
     * @param string $name cookie name
     *
     * @return bool
     */
    public function exists($name);

    /**
     * Set cookie
     *
     * @access public
     *
     * @param string $name cookie name
     * @param mixed $value data value
     * @param int $expire life time
     * @param string $path path access cookie
     * @param string $domain domain access cookie
     * @param bool $secure use SSL?
     * @param bool $httponly disable on JS?
     *
     * @return bool
     */
    public function set($name, $value, $expire = 0, $path = '/', $domain = '', $secure = false, $httponly = true);
}