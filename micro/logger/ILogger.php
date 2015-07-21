<?php

namespace Micro\logger;


interface ILogger
{
    /**
     * Send message in log
     *
     * @access public
     *
     * @param integer $level level number
     * @param string $message message to write
     *
     * @result void
     */
    public function sendMessage($level, $message);
}