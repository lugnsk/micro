<?php /** MicroResolver */

namespace Micro\base;

use Micro\web\Request;

/**
 * Resolver class file.
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
class Resolver
{
    /** @var string $extensions Extensions in request */
    private $extensions;
    /** @var string $modules Modules in request */
    private $modules;
    /** @var string $controller Controller to run */
    private $controller;
    /** @var string $action Action to run */
    private $action;
    /** @var string $uri converted URL */
    protected $uri;
    /** @var Registry $container Container config */
    protected $container;


    /**
     * Construct resolver
     *
     * @access public
     *
     * @param Request $request Current request
     * @param Registry $registry Container config
     *
     * @result void
     */
    public function __construct( Registry $registry )
    {
        $this->container = $registry;

        if ($this->container->request->isCli()) {
            return;
        }

        $query = $this->container->request->getQueryVar('r') ?: '/default';
        $query = (substr($query, -1) === '/') ? '/default': $query;
        $this->uri = $this->container->router->parse( $query, $this->container->request->getMethod() );

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
                $this->container->request->setQueryVar($val[0], $val[1]);
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
            if (file_exists($this->container->AppDir . $this->extensions . '/extensions/' . $block)) {
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
        $path = $this->container->AppDir . ($this->extensions ?: '');

        foreach ($uriBlocks AS $i => $block) {
            if (!empty($block) && file_exists($path . $this->modules . '/modules/' . $block)) {
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
        $path = $this->container->AppDir . ($this->extensions?:'') . ($this->modules?:'');
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
     * Get application instance
     *
     * @access public
     *
     * @return \Micro\base\Command|\Micro\mvc\controllers\Controller
     */
    public function getApplication()
    {
        if ($this->container->request->isCli()) {
            $console = new Console( $this->container->request->getArguments() );
            $command = $console->getCommand();

            /** @var \Micro\base\Command $command */
            return new $command( $this->container, $console->getParams() );
        }

        /** @var \Micro\mvc\controllers\Controller $cls Controller */
        $cls = $this->getCalculatePath();
        return new $cls ( $this->container, $this->getModules() );
    }
}