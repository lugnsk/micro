<?php /** MicroIdentity */

namespace Micro\web;

use Micro\base\Registry;

/**
 * Identity class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web
 * @version 1.0
 * @since 1.0
 * @abstract
 */
abstract class Identity {
    /** @var string $username user name */
    public $username;
    /** @var string $password user password */
    public $password;
    /** @var string $error error string */
    public $error;

    /**
     * Authenticate
     *
     * @access public
     * @global Registry
     * @return bool
     * @abstract
     */
    abstract public function authenticate();

    /**
     * Initialize identity element
     *
     * @access public
     * @param string $username
     * @param string $password
     * @result void
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->error    = null;
    }

    /**
     * Add data into session
     *
     * @access public
     * @global Registry
     * @param string $name session parameter name
     * @param mixed $value session parameter value
     * @return mixed
     */
    public function addSession($name, $value) {
        return Registry::get('session')->$name = $value;
    }

    /**
     * Add data into cookie
     *
     * @access public
     * @global Registry
     * @param string $name cookie name
     * @param mixed $value data value
     * @param int $expire life time
     * @param string $path path access cookie
     * @param string $domain domain access cookie
     * @param bool $secure use SSL?
     * @param bool $httpOnly disable on JS?
     * @return mixed
     */
    public function addCookie($name, $value, $expire=0, $path='/', $domain='', $secure=false, $httpOnly=true) {
        return Registry::get('cookie')->set($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }
}