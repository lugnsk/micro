<?php /** MicroMail */

namespace Micro\web\helpers;

/**
 * MMail class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web\helpers
 * @version 1.0
 * @since 1.0
 */
final class MMail
{
	/** @property string $form sender mail */
	private $from;
	/** @property string $fromName sender name */
	private $fromName = "";
	/** @property string $type type of message */
	private $type = "text/html";
	/** @property string $encoding encoding */
	private $encoding = "utf-8";
	/** @property bool $notify notification of read */
	private $notify = false;

	/**
	 * Constructor for class
	 *
	 * @access public
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
	 * @return boolean
	 */
	public function send($to, $subject, $message) {
		$from = "=?utf-8?B?" . base64_encode($this->fromName) . "?=" . " <" . $this->from . ">";

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