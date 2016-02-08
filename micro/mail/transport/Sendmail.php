<?php /** MicroTransportSendmail */

namespace Micro\Mail\Transport;

use Micro\Mail\IMessage;

/**
 * Class Sendmail
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Mail\Transport
 * @version 1.0
 * @since 1.0
 */
class Sendmail extends Transport
{
    /**
     * @inheritdoc
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
