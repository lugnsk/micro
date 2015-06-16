<?php /** MicroRequest */

namespace Micro\web;

use Micro\Micro;

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

        $this->data = [
            'query'   => isset($_GET) ? $_GET : FALSE,
            'post'    => isset($_POST) ? $_POST : FALSE,
            'files'   => isset($_FILES) ? $_FILES : FALSE,
            'cookie'  => isset($_COOKIE) ? $_COOKIE : FALSE,
            'server'  => isset($_SERVER) ? $_SERVER : FALSE,
            'session' => isset($_SESSION) ? $_SESSION : FALSE
        ];

        unset($_GET, $_POST, $_FILES, $_COOKIE, $_SERVER, $_SESSION, $_REQUEST, $GLOBALS);
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
        return $this->data['server']['REQUEST_METHOD'];
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
        return !empty($this->data['server']['HTTP_X_REQUEST_WITH']) &&
        $this->data['server']['HTTP_X_REQUEST_WITH'] === 'XMLHttpRequest';
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
        return !empty($this->data['server']['REMOTE_ADDR']) ? $this->data['server']['REMOTE_ADDR'] : '127.0.0.1';
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
    public function getBrowser( $agent = null )
    {
        return get_browser( $agent ?: $this->data['server']['HTTP_USER_AGENT'], true);
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
        return array_key_exists($name, $this->data[$storage]) ? $this->data[$storage][$name] : FALSE;
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
    public function getServerVar( $name )
    {
        return $this->getVar($name, 'server');
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
    public function getQueryVar( $name )
    {
        return $this->getVar($name,'query');
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
    public function getPostVar( $name )
    {
        return $this->getVar($name, 'post');
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
    public function getCookieVar( $name )
    {
        return $this->getVar($name, 'cookie');
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
    public function getSessionVar( $name )
    {
        return $this->getVar($name, 'session');
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
    public function getFiles( $className = '\Micro\web\Uploader' )
    {
        if ( !is_array($this->data['files']) ) {
            return false;
        }

        /** @var \Micro\web\Uploader $files */
        $files = new $className( $this->data['files'] );
        return $files;
    }
}