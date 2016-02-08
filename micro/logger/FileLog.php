<?php /** MicroFileLogger */

namespace Micro\Logger;

use Micro\Base\Exception;
use Micro\Base\IContainer;

/**
 * File logger class file.
 *
 * Writer logs in file
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Logger
 * @version 1.0
 * @since 1.0
 */
class FileLog extends Log
{
    /** @var resource $connect File handler */
    protected $connect;


    /**
     * Open file for write messages
     *
     * @access public
     *
     * @param IContainer $container
     * @param array $params configuration params
     *
     * @result void
     * @throws Exception
     */
    public function __construct(IContainer $container, array $params = [])
    {
        parent::__construct($container, $params);

        if (is_writable($params['filename']) || is_writable(dirname($params['filename']))) {
            $this->connect = fopen($params['filename'], 'a+');
        } else {
            throw new Exception('Directory or file "' . $params['filename'] . '" is read-only');
        }
    }

    /**
     * @inheritdoc
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
