<?php /** MicroInterfaceRequest */

namespace Micro\Web;

/**
 * Interface IRequest
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Web
 * @version 1.0
 * @since 1.0
 * @interface
 */
interface IRequest
{
    /**
     * Get flag of running as CLI
     *
     * @access public
     *
     * @return bool
     */
    public function isCli();

    /**
     * Check request is AJAX ?
     *
     * @access public
     *
     * @return bool
     */
    public function isAjax();

    /**
     * Get request method
     *
     * @access public
     *
     * @return string
     */
    public function getMethod();

    /**
     * Get user IP-address
     *
     * @access public
     *
     * @return string
     */
    public function getUserIP();

    /**
     * Get browser data from user user agent string
     *
     * @access public
     *
     * @param null|string $agent User agent string
     *
     * @return mixed
     */
    public function getBrowser($agent = null);

    /**
     * Get arguments from command line
     *
     * @access public
     *
     * @return array
     */
    public function getArguments();

    /**
     * Get files mapper
     *
     * @access public
     *
     * @param string $className Class name of mapper
     *
     * @return mixed
     */
    public function getFiles($className = '\Micro\web\Uploader');

    // Storage's

    /**
     * Get all data from storage
     *
     * @access public
     *
     * @param string $name Storage name
     *
     * @return mixed
     */
    public function getStorage($name);

    /**
     * Set all data into storage
     *
     * @access public
     *
     * @param string $name Storage name
     * @param array $data Any data
     *
     * @return void
     */
    public function setStorage($name, array $data = []);

    // Getters

    /**
     * Get any var from Request storage
     *
     * @access public
     *
     * @param string $name Key name
     * @param string $storage Storage name
     *
     * @return bool
     */
    public function getVar($name, $storage);

    /**
     * Get value by key from query storage
     *
     * @access public
     *
     * @param string $name Key name
     *
     * @return bool
     */
    public function query($name);

    /**
     * Get value by key from post storage
     *
     * @access public
     *
     * @param string $name Key name
     *
     * @return bool
     */
    public function post($name);

    /**
     * Get value by key from cookie storage
     *
     * @access public
     *
     * @param string $name Key name
     *
     * @return bool
     */
    public function cookie($name);

    /**
     * Get value by key from session storage
     *
     * @access public
     *
     * @param string $name Key name
     *
     * @return bool
     */
    public function session($name);

    /**
     * Get value by key from server storage
     *
     * @access public
     *
     * @param string $name Key name
     *
     * @return bool
     */
    public function server($name);

    // Setters

    /**
     * Set value into storage
     *
     * @access public
     *
     * @param string $name Key name
     * @param string $value Key value
     * @param string $storage Storage name
     *
     * @return void
     */
    public function setVar($name, $value, $storage);

    /**
     * Set value into query storage
     *
     * @access public
     *
     * @param string $name Key name
     * @param string $value Key value
     *
     * @return void
     */
    public function setQuery($name, $value);

    /**
     * Set value into post storage
     *
     * @access public
     *
     * @param string $name Key name
     * @param string $value Key value
     *
     * @return void
     */
    public function setPost($name, $value);

    /**
     * Set value into cookie storage
     *
     * @access public
     *
     * @param string $name Key name
     * @param string $value Key value
     *
     * @return void
     */
    public function setCookie($name, $value);

    /**
     * Set value into session storage
     *
     * @access public
     *
     * @param string $name Key name
     * @param string $value Key value
     *
     * @return void
     */
    public function setSession($name, $value);

    // Unset's

    /**
     * Unset var into storage
     *
     * @access public
     *
     * @param string $name Key name
     * @param string $storage Storage name
     *
     * @return void
     */
    public function unsetVar($name, $storage);

    /**
     * Unset var into query storage
     *
     * @access public
     *
     * @param string $name Key name
     *
     * @return void
     */
    public function unsetQuery($name);

    /**
     * Unset var into post storage
     *
     * @access public
     *
     * @param string $name Key name
     *
     * @return void
     */
    public function unsetPost($name);

    /**
     * Unset var into session storage
     *
     * @access public
     *
     * @param string $name Key name
     *
     * @return void
     */
    public function unsetSession($name);
}
