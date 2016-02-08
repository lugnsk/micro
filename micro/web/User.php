<?php /** MicroUser */

namespace Micro\Web;

use Micro\Base\IContainer;

/**
 * Micro user class file
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Web
 * @version 1.0
 * @since 1.0
 */
class User implements IUser
{
    /** @var IContainer $container */
    protected $container;

    /**
     * @access public
     * @param array $config
     * @result void
     */
    public function __construct(array $config)
    {
        $this->container = $config['container'];
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function isGuest()
    {
        return !$this->container->session || !$this->container->session->UserID;
    }

    /**
     * @inheritdoc
     */
    public function getID()
    {
        return (!$this->isGuest()) ? $this->container->session->UserID : false;
    }

    /**
     * @inheritdoc
     */
    public function login($userId)
    {
        $this->setID($userId);
    }

    /**
     * @inheritdoc
     */
    public function setID($id)
    {
        $this->container->session->UserID = $id;
    }

    /**
     * @inheritdoc
     */
    public function logout()
    {
        if (!$this->isGuest()) {
            $this->setID(null);
            $this->container->session->destroy();
        }
    }

    /**
     * @inheritdoc
     */
    public function getCaptcha()
    {
        return $this->container->session->captchaCode;
    }

    /**
     * @inheritdoc
     */
    public function setCaptcha($code)
    {
        $this->container->session->captchaCode = md5($code);
    }

    /**
     * @inheritdoc
     */
    public function checkCaptcha($code)
    {
        if (!$this->container->session->captchaCode) {
            return null;
        }

        return $this->container->session->captchaCode === md5($code);
    }
}
