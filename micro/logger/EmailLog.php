<?php /** MicroEmailLogger */

namespace Micro\Logger;

use Micro\Base\IContainer;
use Micro\Mail\Message;

/**
 * Email logger class file.
 *
 * Sender email for logger
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Logger
 * @version 1.0
 * @since 1.0
 */
class EmailLog extends Log
{
    /** @var string $from email for sender attribute */
    private $from;
    /** @var string $type message attribute */
    private $type = 'text/plain';
    /** @var string $to message recipient */
    private $to;
    /** @var string $subject message theme */
    private $subject;

    /**
     * @inheritdoc
     */
    public function __construct(IContainer $container, array $params = [])
    {
        parent::__construct($container, $params);

        $this->from    = !empty($params['from']) ? $params['from'] : getenv('SERVER_ADMIN');
        $this->to      = !empty($params['to']) ? $params['to'] : $this->from;
        $this->subject = $params['subject'] ?: getenv('SERVER_NAME') . ' log message';
    }

    /**
     * @inheritdoc
     */
    public function sendMessage($level, $message)
    {
        $mail = new Message($this->from);

        $mail->setTo($this->to);
        $mail->setText(ucfirst($level) . ': ' . $message, $this->type);

        $this->container->mail->send($mail);
    }
}
