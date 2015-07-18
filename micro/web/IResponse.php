<?php

namespace Micro\web;

interface IResponse
{
    /**
     * Create and initialize response
     *
     * @access public
     *
     * @param string $body response body
     * @param int $status HTTP status code, default 200
     * @param string $message HTTP status message, default OK
     * @param array $headers HTTP headers
     *
     * @result void
     */
    public function __construct($body = '', $status = 200, $message = null, array $headers = []);

    /**
     * Set status for response
     *
     * @access public
     *
     * @param int $status Code for a new status
     * @param string|null $message Message for a new status
     *
     * @return void
     */
    public function setStatus($status = 200, $message = null);

    /**
     * Set HTTP status message
     *
     * @access public
     *
     * @param string $message New message
     *
     * @return void
     */
    public function setStatusMessage($message = '');

    /**
     * Get HTTP status message from HTTP status code
     *
     * @access public
     *
     * @param int $code Code for get message
     *
     * @return string
     */
    public function getStatusMessageFromCode($code = 200);

    /**
     * Resets all headers
     *
     * @access public
     *
     * @param array $headers New headers array
     *
     * @return void
     */
    public function setHeaders(array $headers = []);

    /**
     * Set body for response
     *
     * @access public
     *
     * @param string $data Data for HTTP response body
     *
     * @return void
     */
    public function setBody($data = '');

    /**
     * Add header into headers array
     *
     * @access public
     *
     * @param string $name Name of header
     * @param string $value Value header
     * @param bool $replace Replaced if exists?
     *
     * @return void
     */
    public function addHeader($name, $value, $replace = true);

    /**
     * Get header if defined
     *
     * @access public
     *
     * @param string $name Name of header to get
     *
     * @return string|null
     */
    public function getHeader($name);

    /**
     * Set HTTP version
     *
     * @access public
     *
     * @param string $version
     *
     * @return void
     */
    public function setHttpVersion($version = 'HTTP/1.1');

    /**
     * Get HTTP Content Type
     *
     * @access public
     *
     * @return string
     */
    public function getContentType();

    /**
     * Set content type of HTTP body
     *
     * @access public
     *
     * @param string $newType New HTTP content type
     *
     * @return void
     */
    public function setContentType($newType = '');

    /**
     * Public convert response to string (for send to client) and send headers
     *
     * @access public
     *
     * @return void
     */
    public function send();

    /**
     * Send headers into browser
     *
     * @access public
     *
     * @return void
     */
    public function sendHeaders();

    /**
     * Return current body
     *
     * @access public
     *
     * @return string
     */
    public function sendBody();
}