<?php

namespace Micro\base;

class Logger
{
    protected $loggers = array(); // Подключенные логеры

    public static $supportedLevels = array(
        'emergency', // Авария
        'alert',     // Тревога
        'critical',  // Критично
        'error',     // Ошибка
        'warning',   // Предупреждение
        'notice',    // Замечание
        'info',      // Информация
        'debug'      // Отладка
    );


    /**
     * Export loggers
     *
     * @access public
     * @param array $params
     * @result void
     */
    public function __construct($params=[])
    {
        foreach ($params['loggers'] AS $name=>$log) {
            if (!array_key_exists('class', $log) OR !class_exists($log['class'])) {
                continue;
            }

            if (!array_key_exists('levels', $log) OR empty($log['levels'])) {
                continue;
            }

            $this->loggers[$name] = new $log['class']($log);
        }
    }

    /**
     * Send message to loggers
     *
     * @access public
     * @param string $level
     * @param string $message
     * @result void
     */
    public function send($level, $message)
    {
        foreach ($this->loggers AS $log) {
            /** @var \Micro\loggers\LogInterface $log */
            if ($log->isSupportedLevel($level)) {
                $log->sendMessage($level, $message);
            }
        }
    }
}