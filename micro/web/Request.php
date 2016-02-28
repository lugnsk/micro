<?php /** MicroRequest */

namespace Micro\Web;

/**
 * Request class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Web
 * @version 1.0
 * @since 1.0
 */
class Request implements IRequest
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
     * @inheritdoc
     */
    public function isCli()
    {
        return $this->cli;
    }

    /**
     * @inheritdoc
     */
    public function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUEST_WITH']) && $_SERVER['HTTP_X_REQUEST_WITH'] === 'XMLHttpRequest';
    }

    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @inheritdoc
     */
    public function getUserIP()
    {
        return !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
    }

    /**
     * @inheritdoc
     */
    public function getBrowser($agent = null)
    {
        return get_browser($agent ?: $_SERVER['HTTP_USER_AGENT'], true);
    }

    /**
     * @inheritdoc
     */
    public function getArguments()
    {
        global $argv;

        return $argv;
    }

    /**
     * @inheritdoc
     */
    public function getFiles($className = '\Micro\web\Uploader')
    {
        if (!is_array($_FILES)) {
            return false;
        }

        return new $className($_FILES);
    }

    /**
     * @inheritdoc
     */
    public function getStorage($name)
    {
        return $GLOBALS[$name];
    }

    /**
     * @inheritdoc
     */
    public function setStorage($name, array $data = [])
    {
        $GLOBALS[$name] = $data;
    }

    /**
     * @inheritdoc
     */
    public function query($name)
    {
        return $this->getVar($name, '_GET');
    }

    /**
     * @inheritdoc
     */
    public function getVar($name, $storage)
    {
        return array_key_exists($name, $GLOBALS[$storage]) ? $GLOBALS[$storage][$name] : null;
    }

    /**
     * @inheritdoc
     */
    public function post($name)
    {
        return $this->getVar($name, '_POST');
    }

    /**
     * @inheritdoc
     */
    public function cookie($name)
    {
        return $this->getVar($name, '_COOKIE');
    }

    /**
     * @inheritdoc
     */
    public function session($name)
    {
        return $this->getVar($name, '_SESSION');
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
    public function server($name)
    {
        return $this->getVar($name, '_SERVER');
    }

    /**
     * @inheritdoc
     */
    public function setQuery($name, $value)
    {
        $this->setVar($name, $value, '_GET');
    }

    /**
     * @inheritdoc
     */
    public function setVar($name, $value, $storage)
    {
        $GLOBALS[$storage][$name] = $value;
    }

    /**
     * @inheritdoc
     */
    public function setPost($name, $value)
    {
        $this->setVar($name, $value, '_POST');
    }

    /**
     * @inheritdoc
     */
    public function setCookie($name, $value)
    {
        $this->setVar($name, $value, '_COOKIE');
    }

    /**
     * @inheritdoc
     */
    public function setSession($name, $value)
    {
        $this->setVar($name, $value, '_SESSION');
    }

    /**
     * @inheritdoc
     */
    public function unsetQuery($name)
    {
        $this->unsetVar($name, '_GET');
    }

    /**
     * @inheritdoc
     */
    public function unsetVar($name, $storage)
    {
        unset($GLOBALS[$storage][$name]);
    }

    /**
     * @inheritdoc
     */
    public function unsetPost($name)
    {
        $this->unsetVar($name, '_POST');
    }

    /**
     * @inheritdoc
     */
    public function unsetSession($name)
    {
        $this->unsetVar($name, '_SESSION');
    }

    /**
     * @inheritdoc
     */
    public function getRequestPayload()
    {
        return file_get_contents('php://input');
    }
}
