<?php /** MicroMailMessage */

namespace Micro\Mail;

/**
 * Message class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Mail
 * @version 1.0
 * @since 1.0
 * @final
 */
class Message implements IMessage
{
    /** @var string $to Recipient */
    private $to;
    /** @var string $type Doctype */
    private $type = 'text/html';
    /** @var string $encoding encoding */
    private $encoding = 'utf-8';
    /** @var string $subject Subject */
    private $subject;
    /** @var string $text Body */
    private $text;
    /** @var array $headers Headers */
    private $headers = [];
    /** @var array $params Parameters */
    private $params = [];


    /**
     * Message constructor
     *
     * @access public
     *
     * @param string $from
     * @param string $fromName
     *
     * @result void
     */
    public function __construct($from = '', $fromName = '')
    {
        if ($from) {
            $this->setHeaders('From', sprintf('=?utf-8?B?%s?= <%s>', base64_encode($fromName), $from));
        }
    }

    /**
     * @inheritdoc
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @inheritdoc
     */
    public function setTo($mail)
    {
        $this->to = $mail;
    }

    /**
     * @inheritdoc
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @inheritdoc
     */
    public function setSubject($text)
    {
        $this->subject = '=?utf-8?B?' . base64_encode($text) . '?=';
    }

    /**
     * @inheritdoc
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @inheritdoc
     */
    public function setText($body, $type = 'text/plain', $encoding = 'utf-8')
    {
        $this->text = $body;
        $this->type = $type;
        $this->encoding = $encoding;
    }

    /**
     * @inheritdoc
     */
    public function getHeaders()
    {
        return sprintf("%s\r\nContent-type: %s; charset=%s\r\n",
            implode("\r\n", array_values($this->headers)),
            $this->type,
            $this->encoding
        );
    }

    /**
     * @inheritdoc
     */
    public function setHeaders($name, $value)
    {
        $this->headers[$name] = $name . ': ' . $value;
    }

    /**
     * @inheritdoc
     */
    public function getParams()
    {
        if (!$this->params) {
            return false;
        }

        return implode("\r\n", array_values($this->params)) . "\r\n";
    }

    /**
     * @inheritdoc
     */
    public function setParams($name, $value)
    {
        $this->params[$name] = $name . ': ' . $value;
    }
}
