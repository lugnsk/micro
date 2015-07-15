<?php /** MicroEmailLogger */

namespace Micro\loggers;

use Micro\base\Container;
use Micro\wrappers\Mail;

/**
 * Email logger class file.
 *
 * Sender email for logger
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage loggers
 * @version 1.0
 * @since 1.0
 */
class EmailLogger extends LogInterface
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
     * Constructor initialize logger
     *
     * @access public
     *
     * @param Container $container
     * @param array $params configuration params
     *
     * @result void
     * @throws \Micro\base\Exception
     */
    public function __construct(Container $container, array $params = [])
    {
        parent::__construct($container, $params);

        $this->from = !empty($params['from']) ? $params['from'] : getenv('SERVER_ADMIN');
        $this->to = !empty($params['to']) ? $params['to'] : $this->from;
        $this->subject = !empty($params['subject']) ? $params['subject'] : $this->container->getServerVar('SERVER_NAME') . ' log message';
    }

    /**
     * Send log message
     *
     * @access public
     *
     * @param integer $level level number
     * @param string $message message to write
     *
     * @return void
     */
    public function sendMessage($level, $message)
    {
        $mail = new Mail($this->from);
        $mail->setType($this->type);
        $mail->send($this->to, $this->subject, ucfirst($level) . ': ' . $message);
    }
}