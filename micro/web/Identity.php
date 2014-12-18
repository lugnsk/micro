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
 */
abstract class Identity {
    /** @property mixed $id unique id */
    protected $id;
    /** @property string $username user name */
    public $username;
    /** @property string $password user password */
    public $password;
    /** @var string $error error string */
    public $error;

    /**
     * Authenticate
     *
     * @access public
     * @return bool
     */
    public function authenticate()
    {
        Registry::get('user')->setID($this->getId());
    }

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
        $this->id       = null;
        $this->error    = null;
    }

    /**
     * Get user ID
     *
     * @access public
     * @return integer|null
     */
    final public function getId() {
        return $this->id;
    }

    /**
     * Set Name state for a given value
     *
     * @access public
     * @param string $name cookie name
     * @param mixed $value cookie value
     * @return mixed
     */
    public function setState($name, $value) {
        return Registry::get('cookie')->set($name, $value);
    }
}