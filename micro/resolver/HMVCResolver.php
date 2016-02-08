<?php /** MicroHMVCResolver */

namespace Micro\Resolver;

use Micro\Base\Exception;
use Micro\Mvc\Controllers\IController;

/**
 * hMVC Resolver class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Resolver
 * @version 1.0
 * @since 1.0
 */
class HMVCResolver extends Resolver
{
    /** @var string $uri converted URL */
    protected $uri;
    /** @var string $extensions Extensions in request */
    private $extensions;
    /** @var string $modules Modules in request */
    private $modules;
    /** @var string $controller IController to run */
    private $controller;
    /** @var string $action Action to run */
    private $action;


    /**
     * Get instance application
     *
     * @access public
     *
     * @return IController
     * @throws Exception
     */
    public function getApplication()
    {
        $query = $this->container->request->query('r') ?: '/default';
        $query = (substr($query, -1) === '/') ? substr($query, 0, -1) : $query;

        $this->uri = $this->container->router->parse($query, $this->container->request->getMethod());

        $this->initialize();

        /** @var string $cls */
        $cls = $this->getCalculatePath();

        if (!class_exists($cls)) {
            throw new Exception('Controller ' . $cls . ' not found');
        }

        return new $cls ($this->container, $this->getModules());
    }

    /**
     * Initialize request object
     *
     * @access public
     *
     * @return void
     */
    protected function initialize()
    {
        $key = strpos($this->uri, '?');
        $params = $key ? substr($this->uri, $key + 2) : null;
        $uriBlocks = explode('/', substr($this->uri, 0, $key ?: strlen($this->uri)));

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
                $this->container->request->setQuery($val[0], $val[1]);
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
    protected function prepareExtensions(&$uriBlocks)
    {
        foreach ($uriBlocks AS $i => $block) {
            if (file_exists($this->container->kernel->getAppDir() . $this->extensions . '/extensions/' . $block)) {
                $this->extensions .= '/extensions/' . $block;
                unset($uriBlocks[$i]);
            } else {
                break;
            }
        }
        $this->extensions = str_replace('/', '\\', $this->extensions);
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
    protected function prepareModules(&$uriBlocks)
    {
        $path = $this->container->kernel->getAppDir() . ($this->extensions ?: '');

        foreach ($uriBlocks AS $i => $block) {
            if ($block && file_exists($path . $this->modules . '/modules/' . $block)) {
                $this->modules .= '/modules/' . $block;
                unset($uriBlocks[$i]);
            } else {
                break;
            }
        }

        $this->modules = str_replace('/', '\\', $this->modules);
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
    protected function prepareController(&$uriBlocks)
    {
        $path = $this->container->kernel->getAppDir() . ($this->extensions ?: '') . ($this->modules ?: '');
        $str = array_shift($uriBlocks);

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
    protected function prepareAction(&$uriBlocks)
    {
        $this->action = array_shift($uriBlocks) ?: 'index';
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
        return '\\App' . $this->getExtensions() . $this->getModules() . '\\Controllers\\' . $this->getController();
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
}
