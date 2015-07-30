<?php /** MicroUser */

namespace Micro\web;

use Micro\base\IContainer;

/**
 * Micro user class file
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web\helpers
 * @version 1.0
 * @since 1.0
 */
class User implements IUser
{
    /** @var IContainer $container */
    protected $container;

    public function __construct(array $config)
    {
        $this->container = $config['container'];
    }

    /**
     * Check access by current user
     *
     * @access public
     * @global       Container
     *
     * @param string $permission permission to check
     * @param array $data arguments
     *
     * @return bool
     */
    public function check($permission, array $data = [])
    {
        if (!$this->isGuest()) {
            return $this->container->permission->check($this->getID(), $permission, $data);
        } else {
            return false;
        }
    }

    /**
     * Get state user
     *
     * @access public
     * @global Container
     * @return bool
     */
    public function isGuest()
    {
        return !$this->container->session || !$this->container->session->UserID;
    }

    /**
     * Get user ID
     *
     * @access public
     * @global Container
     * @return bool|integer
     */
    public function getID()
    {
        return (!$this->isGuest()) ? $this->container->session->UserID : false;
    }

    /**
     * Login user
     *
     * @access public
     *
     * @param int|string $userId User identify
     *
     * @return void
     */
    public function login($userId)
    {
        $this->setID($userId);
    }

    /**
     * Set User ID
     *
     * @access public
     * @global      Container
     *
     * @param mixed $id user id
     *
     * @return void
     */
    public function setID($id)
    {
        $this->container->session->UserID = $id;
    }

    /**
     * Logout user
     *
     * @access public
     *
     * @return void
     */
    public function logout()
    {
        if (!$this->isGuest()) {
            $this->setID(null);
            $this->container->session->destroy();
        }
    }

    /**
     * Get captcha code
     *
     * @access public
     * @global Container
     * @return string
     */
    public function getCaptcha()
    {
        return $this->container->session->captchaCode;
    }

    /**
     * Make captcha from source
     *
     * @access public
     *
     * @param string $code source captcha
     *
     * @return void
     */
    public function setCaptcha($code)
    {
        $this->container->session->captchaCode = md5($code);
    }
}