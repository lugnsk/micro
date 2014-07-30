<?php

namespace Micro\loggers;

class FileLogger extends LogInterface
{
    /** @var resource $connect File handler */
    private $connect;


    /**
     * Open file for write messages
     *
     * @access public
     * @param array $params
     * @result void
     * @throws \Micro\base\Exception
     */
    public function __construct($params=[])
    {
        parent::__construct($params);

        if (is_writeable($params['filename']) OR is_writeable(dirname($params['filename']))) {
            $this->connect = fopen($params['filename'], 'a+');
        } else {
            throw new \Micro\base\Exception('Directory or file "'.$params['filename'].'" is read-only');
        }
    }

    /**
     * Send message in file log
     *
     * @access public
     * @param string $level
     * @param string $message
     * @result void
     * @throws \Micro\base\Exception
     */
    public function sendMessage($level, $message)
    {
        if (is_resource($this->connect)) {
            fwrite($this->connect, '['.date('H:i:s d.m.Y').'] '.ucfirst($level).": {$message}\n");
        } else {
            throw new \Micro\base\Exception('Error write log in file.');
        }
    }

    /**
     * Close opened for messages file
     *
     * @access public
     * @result void
     */
    public function __destruct()
    {
        if (is_resource($this->connect)) {
            fclose($this->connect);
        }
    }
}