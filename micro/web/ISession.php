<?php
/**
 * Created by PhpStorm.
 * User: casper
 * Date: 19.07.15
 * Time: 14:28
 */

namespace Micro\web;

/**
 * Interface ISession
 * @package Micro\web
 *
 * @property array $flash FlashMessages
 */
interface ISession
{
    /**
     * Create a new session or load prev session
     *
     * @access public
     * @return void
     */
    public function create();

    /**
     * Destroy session
     *
     * @access public
     * @return void
     */
    public function destroy();

    /**
     * Getter session element
     *
     * @access public
     *
     * @param string $name element name
     *
     * @return mixed
     */
    public function __get($name);

    /**
     * Setter session element
     *
     * @access public
     *
     * @param string $name element name
     * @param mixed $value element value
     *
     * @return void
     */
    public function __set($name, $value);

    /**
     * Is set session element
     *
     * @access public
     *
     * @param string $name element name
     *
     * @return boolean
     */
    public function __isset($name);

    /**
     * Unset session element
     *
     * @access public
     *
     * @param string $name element name
     *
     * @return void
     */
    public function __unset($name);
}