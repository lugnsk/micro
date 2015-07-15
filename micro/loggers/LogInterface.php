<?php /** MicroLogInterface */

namespace Micro\loggers;

use Micro\base\Exception;
use Micro\base\Registry;

/**
 * Base logger class file.
 *
 * Interface for loggers
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage loggers
 * @version 1.0
 * @since 1.0
 */
abstract class LogInterface
{
    /** @var array $supportedLevels supported log levels */
    protected $supportedLevels = array();
    protected $container;

    /**
     * Constructor is a initialize loggers
     *
     * @access public
     *
     * @param array $params configuration params
     *
     * @throws Exception
     * @result void
     */
    public function __construct(Registry $container, array $params = [])
    {
        $this->container = $container;

        $levels = explode(',', strtr(strtolower($params['levels']), ' ', ''));
        foreach ($levels AS $level) {
            if (in_array($level, Logger::$supportedLevels, true)) {
                $this->supportedLevels[] = $level;
            }
        }
        if (!$levels) {
            throw new Exception($this->container, 'Logger ' . get_class($this) . ' empty levels.');
        }
    }

    /**
     * Check support level
     *
     * @access public
     *
     * @param integer $level level number
     *
     * @return bool
     */
    public function isSupportedLevel($level)
    {
        return in_array($level, $this->supportedLevels, false) === false ?: true;
    }

    /**
     * Send log message
     *
     * @access public
     *
     * @param integer $level level number
     * @param string $message message to write
     *
     * @return void
     */
    abstract public function sendMessage($level, $message);
}