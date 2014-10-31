<?php /** MicroRequest */

namespace Micro\web;

use Micro\Micro;

/**
 * Request class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
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


    /**
     * Constructor Request
     *
     * @access public
     * @param array $routes routes array
     * @result void
     */
    public function __construct($routes = [])
    {
        $this->router = new Router(isset($routes['routes']) ? $routes['routes'] : []);
        $this->initialize();
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Initialize request object
     *
     * @access public
     * @return void
     */
    private function initialize()
    {
        $uri = (isset($_GET['r']) OR !empty($_GET['r'])) ? $_GET['r'] : '/';

        if (substr($uri, -1) == '/') {
            $uri = substr($uri, 0, -1);
        }

        $trustUri = $this->router->parse($uri, $this->getMethod());
        $uriBlocks = explode('/', $trustUri);

        if ($uri{0} == '/') {
            array_shift($uriBlocks);
        }

        $this->prepareExtensions($uriBlocks);
        $this->prepareModules($uriBlocks);
        $this->prepareController($uriBlocks);
        $this->prepareAction($uriBlocks);

        if (!empty($uriBlocks)) {
            $uriBlocks = array_values($uriBlocks);
            $countUriBlocks = count($uriBlocks);

            $gets = [];
            for ($i = 0; $i < $countUriBlocks; $i = $i + 2) {
                if (!isset($uriBlocks[$i + 1])) {
                    return;
                }
                $gets[$uriBlocks[$i]] = $uriBlocks[$i + 1];
            }
            $_GET = array_merge($_GET, $gets);
        }
    }

    /**
     * Prepare extensions
     * @access private
     * @param array $uriBlocks uri blocks from URL
     * @return bool
     */
    private function prepareExtensions(&$uriBlocks)
    {
        $extensions = [];
        if (isset(Micro::getInstance()->config['extensions'])) {
            $extensions = Micro::getInstance()->config['extensions'];
        }
        if (!$extensions) {
            return false;
        }

        $path = Micro::getInstance()->config['AppDir'];

        foreach ($uriBlocks AS $i => $block) {
            if (file_exists($path . $this->extensions . '/extensions/' . $block)) {
                $this->extensions .= '/extensions/' . $block;
                unset($uriBlocks[$i]);
            } else {
                break;
            }
        }
        $this->extensions = strtr($this->extensions, '/', '\\');
        return true;
    }

    /**
     * Prepare modules
     *
     * @access private
     * @global Micro
     * @param array $uriBlocks uri blocks from URL
     * @return void
     */
    private function prepareModules(&$uriBlocks)
    {
        $path = Micro::getInstance()->config['AppDir'];

        if ($this->extensions) {
            $path .= $this->extensions;
        }

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
     * @param array $uriBlocks uri blocks from URL
     * @return void
     */
    private function prepareController(&$uriBlocks)
    {
        $path = Micro::getInstance()->config['AppDir'];

        if ($this->extensions) {
            $path .= $this->extensions;
        }
        if ($this->modules) {
            $path .= $this->modules;
        }

        $str = array_shift($uriBlocks);
        if (file_exists(str_replace('\\' , '/' , $path . '/controllers/' . ucfirst($str).'Controller.php' ))) {
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
     * @param array $uriBlocks uri blocks from URL
     * @return void
     */
    private function prepareAction(&$uriBlocks)
    {
        $this->action = ($str = array_shift($uriBlocks)) ? $str : 'index';
    }

    /**
     * Get extensions from request
     *
     * @access public
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
     * @return string
     */
    public function getController()
    {
        return ucfirst($this->controller) . 'Controller';
    }

    /**
     * Get action from request
     *
     * @access public
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}