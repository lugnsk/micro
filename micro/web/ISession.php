<?php /** MicroInterfaceSession */

namespace Micro\Web;

/**
 * Interface ISession
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
 * @property array   $flash FlashMessages
 * @property array   $csrf
 * @property integer $UserID
 * @property string  $captchaCode
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
