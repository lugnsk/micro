<?php /** MicroRequest */

namespace Micro\web;

/**
 * Request class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web
 * @version 1.0
 * @since 1.0
 */
class Request
{
    /** @var bool $cli Is running as CLI */
    protected $cli;
    /** @var array $data Data from request */
    protected $data;


    /**
     * Constructor Request
     *
     * @access public
     *
     * @result void
     */
    public function __construct()
    {
        $this->cli = php_sapi_name() === 'cli';
    }

    /**
     * Get flag of running as CLI
     *
     * @access public
     *
     * @return bool
     */
    public function isCli()
    {
        return $this->cli;
    }

    /**
     * Get request method
     *
     * @access public
     *
     * @return string
     */
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Check request is AJAX ?
     *
     * @access public
     *
     * @return bool
     */
    public function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUEST_WITH']) && $_SERVER['HTTP_X_REQUEST_WITH'] === 'XMLHttpRequest';
    }

    /**
     * Get user IP-address
     *
     * @access public
     *
     * @return string
     */
    public function getUserIP()
    {
        return !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
    }

    /**
     * Get browser data from user user agent string
     *
     * @access public
     *
     * @param null|string $agent User agent string
     *
     * @return mixed
     */
    public function getBrowser($agent = null)
    {
        return get_browser($agent ?: $_SERVER['HTTP_USER_AGENT'], true);
    }

    /**
     * Set value into storage
     *
     * @access public
     *
     * @param string $name Key name
     * @param string $value Key value
     * @param string $storage Storage name
     *
     * @return void
     */
    public function setVar($name, $value, $storage)
    {
        $GLOBALS[$storage][$name] = $value;
    }

    /**
     * Set value into query storage
     *
     * @access public
     *
     * @param string $name Key name
     * @param string $value Key value
     *
     * @return void
     */
    public function setQueryVar($name, $value)
    {
        $this->setVar($name, $value, '_GET');
    }

    /**
     * Set value into post storage
     *
     * @access public
     *
     * @param string $name Key name
     * @param string $value Key value
     *
     * @return void
     */
    public function setPostVar($name, $value)
    {
        $this->setVar($name, $value, '_POST');
    }

    /**
     * Set value into cookie storage
     *
     * @access public
     *
     * @param string $name Key name
     * @param string $value Key value
     *
     * @return void
     */
    public function setCookieVar($name, $value)
    {
        $this->setVar($name, $value, '_COOKIE');
    }

    /**
     * Set value into session storage
     *
     * @access public
     *
     * @param string $name Key name
     * @param string $value Key value
     *
     * @return void
     */
    public function setSessionVar($name, $value)
    {
        $this->setVar($name, $value, '_SESSION');
    }

    /**
     * Get any var from Request storage
     *
     * @access public
     *
     * @param string $name Key name
     * @param string $storage Storage name
     *
     * @return bool
     */
    public function getVar($name, $storage)
    {
        return array_key_exists($name, $GLOBALS[$storage]) ? $GLOBALS[$storage][$name] : false;
    }

    /**
     * Get value by key from server storage
     *
     * @access public
     *
     * @param string $name Key name
     *
     * @return bool
     */
    public function getServerVar($name)
    {
        return $this->getVar($name, '_SERVER');
    }

    /**
     * Get value by key from query storage
     *
     * @access public
     *
     * @param string $name Key name
     *
     * @return bool
     */
    public function getQueryVar($name)
    {
        return $this->getVar($name, '_GET');
    }

    /**
     * Get value by key from post storage
     *
     * @access public
     *
     * @param string $name Key name
     *
     * @return bool
     */
    public function getPostVar($name)
    {
        return $this->getVar($name, '_POST');
    }

    /**
     * Get value by key from cookie storage
     *
     * @access public
     *
     * @param string $name Key name
     *
     * @return bool
     */
    public function getCookieVar($name)
    {
        return $this->getVar($name, '_COOKIE');
    }

    /**
     * Get value by key from session storage
     *
     * @access public
     *
     * @param string $name Key name
     *
     * @return bool
     */
    public function getSessionVar($name)
    {
        return $this->getVar($name, '_SESSION');
    }

    /**
     * Get arguments from command line
     *
     * @access public
     *
     * @return array
     */
    public function getArguments()
    {
        global $argv;

        return $argv;
    }

    /**
     * Get files mapper
     *
     * @access public
     *
     * @param string $className Class name of mapper
     *
     * @return mixed
     */
    public function getFiles($className = '\Micro\web\Uploader')
    {
        if (!is_array($_FILES)) {
            return false;
        }

        /** @var \Micro\web\Uploader $files */
        $files = new $className($_FILES);

        return $files;
    }

    /**
     * Get all data from storage
     *
     * @access public
     *
     * @param string $name Storage name
     *
     * @return mixed
     */
    public function getStorage($name)
    {
        return $GLOBALS[$name];
    }

    /**
     * Set all data into storage
     *
     * @access public
     *
     * @param string $name Storage name
     * @param array $data Any data
     *
     * @return void
     */
    public function setStorage($name, array $data = [])
    {
        $GLOBALS[$name] = $data;
    }
}