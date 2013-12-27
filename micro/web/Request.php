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
    /** @var Router $router router for request */
    private $router;
    /** @var string $extensions extensions in request */
    private $extensions;
    /** @var string $modules modules in request */
    private $modules;
    /** @var string $controller controller to run */
    private $controller;
    /** @var string $action action to run */
    private $action;
    /** @var string $uri current uri */
    private $uri;


    /**
     * Constructor Request
     *
     * @access public
     *
     * @param array $routes routes array
     *
     * @result void
     */
    public function __construct(array $routes = [])
    {
        $this->router = new Router( !empty($routes['routes']) ? $routes['routes'] : [] );

        $this->uri = !empty($_GET['r']) ? $_GET['r']: '/default';
        $this->uri = (substr($this->uri, -1) === '/') ? '/default': $this->uri;
        $this->uri = $this->router->parse($this->uri, $this->getMethod());

        $this->initialize();
    }
    /**
     * Initialize request object
     *
     * @access public
     *
     * @return void
     */
    private function initialize()
    {
        $key = strpos($this->uri, '?');
        $params = $key ? substr($this->uri, $key+2) : null;
        $uriBlocks = explode('/', substr($this->uri, 0, $key?:strlen($this->uri)));

        if (substr($this->uri, 0, 1) === '/') {
            array_shift($uriBlocks);
        }

        $this->prepareExtensions($uriBlocks);
        $this->prepareModules($uriBlocks);
        $this->prepareController($uriBlocks);
        $this->prepareAction($uriBlocks);

        if ($params) {
            $paramBlocks = explode('&', $params);

            foreach ($paramBlocks AS $param) {
                $val = explode('=', $param);
                $_GET[$val[0]] = $val[1];
            }
        }
    }

    /**
     * Prepare extensions
     *
     * @access private
     *
     * @param array $uriBlocks uri blocks from URL
     *
     * @return void
     */
    private function prepareExtensions(&$uriBlocks)
    {
        foreach ($uriBlocks AS $i => $block) {
            if (file_exists(Micro::getInstance()->config['AppDir'] . $this->extensions . '/extensions/' . $block)) {
                $this->extensions .= '/extensions/' . $block;
                unset($uriBlocks[$i]);
            } else {
                break;
            }
        }
        $this->extensions = strtr($this->extensions, '/', '\\');
    }
    /**
     * Prepare modules
     *
     * @access private
     *
     * @global      Micro
     *
     * @param array $uriBlocks uri blocks from URL
     *
     * @return void
     */
    private function prepareModules(&$uriBlocks)
    {
        $path = Micro::getInstance()->config['AppDir'] . ($this->extensions ?: '');

        foreach ($uriBlocks AS $i => $block) {
            if (file_exists($path . $this->modules . '/modules/' . $block)) {
                $this->modules .= '/modules/' . $block;
                unset($uriBlocks[$i]);
            } else {
                break;
            }
        }

        $this->modules = strtr($this->modules, '/', '\\');
    }
    /**
     * Prepare controller
     *
     * @access private
     *
     * @param array $uriBlocks uri blocks from URL
     *
     * @return void
     */
    private function prepareController(&$uriBlocks)
    {
        $path = Micro::getInstance()->config['AppDir'] . ($this->extensions?:'') . ($this->modules?:'');
        $str  = array_shift($uriBlocks);

        if (file_exists(str_replace('\\', '/', $path . '/controllers/' . ucfirst($str) . 'Controller.php'))) {
            $this->controller = $str;
        } else {
            $this->controller = 'default';
            array_unshift($uriBlocks, $str);
        }
    }
    /**
     * Prepare action
     *
     * @access private
     *
     * @param array $uriBlocks uri blocks from URL
     *
     * @return void
     */
    private function prepareAction(&$uriBlocks)
    {
        $this->action = array_shift($uriBlocks) ?: 'index';
    }

    /**
     * Get extensions from request
     *
     * @access public
     *
     * @return string
     */
    public function getExtensions()
    {
        return $this->extensions;
    }
    /**
     * Get modules from request
     *
     * @access public
     *
     * @return string
     */
    public function getModules()
    {
        return $this->modules;
    }
    /**
     * Get controller from request
     *
     * @access public
     *
     * @return string
     */
    public function getController()
    {
        return ucfirst($this->controller) . 'Controller';
    }
    /**
     * Get calculate path to controller
     *
     * @access public
     *
     * @return string
     */
    public function getCalculatePath()
    {
        return 'App' . $this->getExtensions() . $this->getModules() . '\\controllers\\' . $this->getController();
    }
    /**
     * Get action from request
     *
     * @access public
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
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
    public function getBrowser( $agent = null )
    {
        return get_browser( $agent ?: $_SERVER['HTTP_USER_AGENT'], true);
    }
}