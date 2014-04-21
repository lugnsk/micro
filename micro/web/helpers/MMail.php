<?php

/**
 * MMail class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license http://opensource.org/licenses/MIT
 * @package micro
 * @subpackage web
 * @subpackage helpers
 * @version 1.0
 * @since 1.0
 */
class MMail
{
	private $from;
	private $fromName = "";
	private $type = "text/html";
	private $encoding = "utf-8";
	private $notify = false;

	/**
	 * Constructor for class
	 *
	 * @access puublic
	 * @param string $from
	 * @result void
	 */
	public function __construct($from='') {
		$this->from = $from;
	}
	/**
	 * Setting attribute from
	 *
	 * @access public
	 * @param string $from e-mail
	 * @result void
	 */
	public function setFrom($from) {
		$this->from = $from;
	}
	/**
	 * Setting attribute from name
	 *
	 * @access public
	 * @param string $name
	 * @result void
	 */
	public function setFromName($name) {
		$this->fromName = $name;
	}
	/**
	 * Setting message type
	 *
	 * @access public
	 * @param string $type mime-type
	 * @result void
	 */
	public function setType($type) {
		$this->type = $type;
	}
	/**
	 * Setting attribute notification
	 * @access public
	 * @param boolean $notify
	 * @result void
	 */
	public function setNotify($notify) {
		$this->notify = $notify;
	}
	/**
	 * Setting attribute encoding
	 *
	 * @access public
	 * @param string $encoding
	 * @result void
	 */
	public function setEncoding($encoding) {
		$this->encoding = $encoding;
	}
	/**
	 * Sending message
	 *
	 * @access public
	 * @param string $to
	 * @param string $subject
	 * @param string $message
	 * @result boolean
	 */
	public function send($to, $subject, $message) {
		$from = "=?utf-8?B?" . base64_encode($this->from_name) . "?=" . " <" . $this->from . ">";

		$headers = "From: " . $from .
			"\r\nReply-To: " . $from .
			"\r\nContent-type: " . $this->type .
			"; charset=" . $this->encoding . "\r\n";

		if ($this->notify) {
			$headers .= "Disposition-Notification-To: ".$this->from."\r\n";
		}
		$subject = "=?utf-8?B?".base64_encode($subject)."?=";

		return mail($to, $subject, $message, $headers);
	}
}