<?php /** SecurityMicro */

namespace Micro\web;

/**
 * Security class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web
 * @version 1.0
 */
class Security {
    /**
     * Constructor
     *
     * @access public
     * @param array $config configuration array
     * @result void
     */
    public function __construct($config=[])
    {
        $this->encodeInputVars();
    }

    /**
     * Encode input arrays
     *
     * @access public
     * @return void
     */
    public function encodeInputVars()
    {
        $_GET = array_map('strip_tags',$_GET);
        $_POST = array_map('strip_tags',$_POST);
        $_COOKIE = array_map('strip_tags',$_COOKIE);

        $_GET = array_map('htmlspecialchars', $_GET);
        $_POST = array_map('htmlspecialchars', $_POST);
        $_COOKIE = array_map('htmlspecialchars', $_COOKIE);

        $_GET = array_map('trim', $_GET);
        $_POST = array_map('trim', $_POST);
        $_COOKIE = array_map('trim', $_COOKIE);

        $_GET = array_map('addslashes', $_GET);
        $_POST = array_map('addslashes', $_POST);
        $_COOKIE = array_map('addslashes', $_COOKIE);
    }
}