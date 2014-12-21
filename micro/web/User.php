<?php /** MicroUser */

namespace Micro\web;

use Micro\base\Registry;

/**
 * Micro user class file
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web\helpers
 * @version 1.0
 * @since 1.0
 */
class User
{
    /**
     * Get state user
     *
     * @access public
     * @return bool
     */
    public function isGuest()
    {
        return ((!Registry::get('session')) OR empty(Registry::get('session')->UserID));
    }

    /**
     * Set User ID
     *
     * @access public
     * @param mixed $id user id
     * @return void
     */
    public function setID($id)
    {
        Registry::get('session')->UserID = $id;
    }

    /**
     * Get user ID
     *
     * @access public
     * @return bool|integer
     */
    public function getID()
    {
        return (!$this->isGuest()) ? Registry::get('session')->UserID : false;
    }

    /**
     * Check access by current user
     *
     * @access public
     * @param string $permission permission to check
     * @param array $data arguments
     * @return bool
     */
    public function check($permission, $data = [])
    {
        if (!$this->isGuest()) {
            return Registry::get('permission')->check($this->getID(), $permission, $data);
        } else {
            return false;
        }
    }

    /**
     * Get captcha code
     *
     * @access public
     * @return string
     */
    public function getCaptcha()
    {
        return Registry::get('session')->captchaCode;
    }

    /**
     * Make captcha from source
     *
     * @access public
     * @param string $code source captcha
     * @return string
     */
    public function makeCaptcha($code)
    {
        return md5($code);
    }
}