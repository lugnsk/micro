<?php /** MicroFileLogger */

namespace Micro\loggers;

use Micro\base\Exception;

/**
 * File logger class file.
 *
 * Writer logs in file
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
class FileLogger extends LogInterface
{
    /** @var resource $connect File handler */
    protected $connect;


    /**
     * Open file for write messages
     *
     * @access public
     * @param array $params configuration params
     * @result void
     * @throws Exception
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);

        if (is_writeable($params['filename']) OR is_writeable(dirname($params['filename']))) {
            $this->connect = fopen($params['filename'], 'a+');
        } else {
            throw new Exception('Directory or file "' . $params['filename'] . '" is read-only');
        }
    }

    /**
     * Send message in file log
     *
     * @access public
     * @param integer $level level number
     * @param string $message message to write
     * @result void
     * @throws Exception error write to log
     */
    public function sendMessage($level, $message)
    {
        if (is_resource($this->connect)) {
            fwrite($this->connect, '[' . date('H:i:s d.m.Y') . '] ' . ucfirst($level) . ": {$message}\n");
        } else {
            throw new Exception('Error write log in file.');
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