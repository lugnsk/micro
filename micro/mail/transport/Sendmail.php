<?php /** MicroTransportSendmail */

namespace Micro\mail\transport;

use Micro\mail\IMessage;

/**
 * Class Sendmail
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
class Sendmail extends Transport
{
    /**
     * Send message
     *
     * @access public
     *
     * @param IMessage $message
     *
     * @return bool
     */
    public function send(IMessage $message)
    {
        return mail(
            $message->getTo(),
            $message->getSubject(),
            $message->getText(),
            $message->getHeaders(),
            $message->getParams()
        );
    }
}
