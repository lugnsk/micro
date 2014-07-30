<?php

namespace Micro\loggers;

abstract class LogInterface
{
    protected $supportedLevels = array();

    public function __construct($params = [])
    {
        $levels = explode(',', str_replace(' ', '', strtolower($params['levels'])));
        foreach ($levels AS $level) {
            if (array_search($level, \Micro\base\Logger::$supportedLevels)) {
                $this->supportedLevels[] = $level;
            }
        }
        if (!$levels) {
            throw new \Micro\base\Exception('Logger '.get_class($this).' empty levels.');
        }
    }

    public function isSupportedLevel($level)
    {
        return (array_search($level, $this->supportedLevels)===FALSE) ? false : true;
    }

    abstract public function sendMessage($level, $message);
}