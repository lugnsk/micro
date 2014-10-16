<?php /** MicroMail */

namespace Micro\wrappers;

/**
 * Mail class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage wrappers
 * @version 1.0
 * @since 1.0
 */
final class Mail
{
    /** @var string $form sender mail */
    private $from;
    /** @var string $fromName sender name */
    private $fromName = "";
    /** @var string $type type of message */
    private $type = "text/html";
    /** @var string $encoding encoding */
    private $encoding = "utf-8";
    /** @var bool $notify notification of read */
    private $notify = false;

    /**
     * Constructor for class
     *
     * @access public
     * @param string $from sender e-mail
     * @result void
     */
    public function __construct($from = '')
    {
        $this->from = $from;
    }

    /**
     * Setting attribute from
     *
     * @access public
     * @param string $from e-mail
     * @result void
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * Setting attribute from name
     *
     * @access public
     * @param string $name name for e-mail
     * @result void
     */
    public function setFromName($name)
    {
        $this->fromName = $name;
    }

    /**
     * Setting message type
     *
     * @access public
     * @param string $type mime-type
     * @result void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Setting attribute notification
     * @access public
     * @param boolean $notify target read email notify?
     * @result void
     */
    public function setNotify($notify)
    {
        $this->notify = $notify;
    }

    /**
     * Setting attribute encoding
     *
     * @access public
     * @param string $encoding set email encoding
     * @result void
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * Sending message
     *
     * @access public
     * @param string $to e-mail recipient
     * @param string $subject subject for message
     * @param string $message message text
     * @return boolean
     */
    public function send($to, $subject, $message)
    {
        $from = "=?utf-8?B?" . base64_encode($this->fromName) . "?=" . " <" . $this->from . ">";

        $headers = "From: " . $from .
            "\r\nReply-To: " . $from .
            "\r\nContent-type: " . $this->type .
            "; charset=" . $this->encoding . "\r\n";

        if ($this->notify) {
            $headers .= "Disposition-Notification-To: " . $this->from . "\r\n";
        }
        $subject = "=?utf-8?B?" . base64_encode($subject) . "?=";

        return mail($to, $subject, $message, $headers);
    }
}