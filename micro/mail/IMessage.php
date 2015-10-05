<?php /** MicroIMessage */

namespace Micro\mail;

/**
 * Interface IMessage
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage mail
 * @version 1.0
 * @since 1.0
 */
interface IMessage
{
    /**
     * Set recipes
     *
     * @access public
     *
     * @param string $mail One or more emails
     *
     * @return void
     */
    public function setTo($mail);

    /**
     * Change subject
     *
     * @access public
     *
     * @param string $text Subject text
     *
     * @return void
     */
    public function setSubject($text);

    /**
     * Set message text
     *
     * @access public
     *
     * @param string $body Message text
     * @param string $type Message type
     * @param string $encoding Message encoding
     *
     * @return void
     */
    public function setText($body, $type = 'text/plain', $encoding = 'utf-8');

    /**
     * Set headers
     *
     * @access public
     *
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    public function setHeaders($name, $value);

    /**
     * Set params
     *
     * @access public
     *
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    public function setParams($name, $value);

    /**
     * Get recipes
     *
     * @access public
     * @return string
     */
    public function getTo();

    /**
     * Get subject
     *
     * @access public
     * @return string
     */
    public function getSubject();

    /**
     * Get text body
     *
     * @access public
     * @return string
     */
    public function getText();

    /**
     * Get headers as string
     *
     * @access public
     * @return string
     */
    public function getHeaders();

    /**
     * Get params as string
     *
     * @access public
     * @return string
     */
    public function getParams();
}
