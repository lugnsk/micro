<?php /** MicroInterfaceOutput */

namespace Micro\web;

/**
 * Interface IOutput
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web
 * @version 1.0
 * @since 1.0
 */
interface IOutput
{
    /**
     * Send data to browser|console
     *
     * @access public
     * @return void
     */
    public function send();
}
