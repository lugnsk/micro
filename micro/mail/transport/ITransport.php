<?php /** MicroMailTransport */

namespace Micro\mail\transport;

use Micro\mail\IMessage;

/**
 * Interface ITransport
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage mail\transport
 * @version 1.0
 * @since 1.0
 */
interface ITransport
{
    /**
     * Send message
     *
     * @access public
     *
     * @param IMessage $message Message to send
     *
     * @return bool
     */
    public function send(IMessage $message);
}
