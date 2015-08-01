<?php /** MicroInterfaceLogger */

namespace Micro\logger;

/**
 * Interface ILogger
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage logger
 * @version 1.0
 * @since 1.0
 */
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

    /**
     * Check support level
     *
     * @access public
     *
     * @param integer $level level number
     *
     * @return bool
     */
    public function isSupportedLevel($level);
}
