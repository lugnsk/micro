<?php /** MicroFileLogger */

namespace Micro\loggers;

use Micro\base\Exception;
use Micro\base\Registry;

/**
 * File logger class file.
 *
 * Writer logs in file
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
class FileLogger extends LogInterface
{
    /** @var resource $connect File handler */
    protected $connect;


    /**
     * Open file for write messages
     *
     * @access public
     *
     * @param Registry $container
     * @param array $params configuration params
     *
     * @result void
     * @throws Exception
     */
    public function __construct(Registry $container, array $params = [])
    {
        parent::__construct($container, $params);

        if (is_writable($params['filename']) OR is_writable(dirname($params['filename']))) {
            $this->connect = fopen($params['filename'], 'a+');
        } else {
            throw new Exception($this->container, 'Directory or file "' . $params['filename'] . '" is read-only');
        }
    }

    /**
     * Send message in file log
     *
     * @access public
     *
     * @param integer $level level number
     * @param string $message message to write
     *
     * @result void
     * @throws Exception error write to log
     */
    public function sendMessage($level, $message)
    {
        if (is_resource($this->connect)) {
            fwrite($this->connect, '[' . date('H:i:s d.m.Y') . '] ' . ucfirst($level) . ": {$message}\n");
        } else {
            throw new Exception($this->container, 'Error write log in file.');
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