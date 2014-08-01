<?php

namespace Micro\loggers;

class EmailLogger extends LogInterface
{
    private $from;
    private $type = 'text/plain';
    private $to;
    private $subject;

    public function __construct($params=[])
    {
        parent::__construct($params);

        $this->from = isset($params['from']) ? $params['from'] : getenv("SERVER_ADMIN");
        $this->to = isset($params['to']) ? $params['to'] : $this->from;
        $this->subject = isset($params['subject']) ? $params['subject'] : $_SERVER['SERVER_NAME'].' log message';
    }

    public function sendMessage($level, $message)
    {
        $mail = new \Micro\web\helpers\Mail($this->from);
        $mail->setType($this->type);
        $mail->send($this->to, $this->subject, ucfirst($level).': '.$message );
    }
}