<?php /** MicroLogInterface */

namespace Micro\loggers;

use Micro\base\Logger;
use Micro\base\Exception;

/**
 * Base logger class file.
 *
 * Interface for loggers
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage loggers
 * @version 1.0
 * @since 1.0
 */
abstract class LogInterface
{
    /** @var array $supportedLevels */
    protected $supportedLevels = array();

    /**
     * Constructor is a initialize loggers
     *
     * @access public
     * @param array $params
     * @throws Exception
     * @result void
     */
    public function __construct($params = [])
    {
        $levels = explode(',', strtr(strtolower($params['levels']), ' ', ''));
        foreach ($levels AS $level) {
            if (array_search($level, Logger::$supportedLevels)) {
                $this->supportedLevels[] = $level;
            }
        }
        if (!$levels) {
            throw new Exception('Logger '.get_class($this).' empty levels.');
        }
    }

    /**
     * Check support level
     *
     * @access public
     * @param $level
     * @return bool
     */
    public function isSupportedLevel($level)
    {
        return (array_search($level, $this->supportedLevels)===FALSE) ? false : true;
    }

    /**
     * Send log message
     *
     * @access public
     * @param $level
     * @param $message
     * @return void
     */
    abstract public function sendMessage($level, $message);
}