<?php /** Micro */

namespace Micro;

use Micro\base\Autoload;
use Micro\base\Container;
use Micro\base\Dispatcher;
use Micro\base\IContainer;
use Micro\cli\DefaultConsoleCommand;
use Micro\resolver\ConsoleResolver;
use Micro\resolver\HMVCResolver;
use Micro\web\IOutput;
use Micro\web\IRequest;
use Micro\web\Response;

/**
 * Micro class file.
 *
 * Base class for initialize MicroPHP, used as bootstrap framework.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class Micro
{
    /** @const string VERSION Version framework */
    const VERSION = '1.1';


    /** @var string $environment Application environment */
    protected $environment = 'devel';
    /** @var bool $debug Debug-mode flag */
    protected $debug = true;
    /** @var float $startTime Time of start framework */
    protected $startTime;
    /** @var bool $loaded Micro loaded flag */
    protected $loaded;
    /** @var IContainer $container Container is a container for components and options */
    protected $container;


    /**
     * Initialize framework
     *
     * @access public
     *
     * @param string $environment Application environment: devel , prod , test
     * @param bool $debug Debug-mode flag
     *
     * @result void
     */
    public function __construct($environment = 'devel', $debug = true)
    {
        $this->environment = $environment;
        $this->debug = (bool)$debug;

        $this->loaded = false;

          if ($this->debug) {
            $this->startTime = microtime(true);
        }
    }

    /**
     * Clone application
     *
     * @access public
     *
     * @return void
     */
    public function __clone()
    {
        if ($this->debug) {
            $this->startTime = microtime(true);
        }

        $this->loaded = false;
        $this->container = null;
    }

    /**
     * Default config path
     *
     * @return string
     */
    protected function getConfig()
    {
        return false;
    }

    /**
     * Running application
     *
     * @access public
     *
     * @param IRequest $request Request object
     * @param string $configPath Path to config file
     *
     * @return Response
     * @throws \Exception
     */
    public function run(IRequest $request)
    {
        if (!$this->loaded) {
            $this->loader();
        }

        $this->container->request = $request;

        try {
            return $this->doRun();
        } catch (\Exception $e) {
            if ($this->debug) {
                $this->container->dispatcher->signal('kernel.stopped', ['exception' => $e]);
                throw $e;
            }

            return $this->doException($e);
        }
    }

    /**
     * Boot Loader
     *
     * @access public
     *
     * @return void
     */
    public function loader()
    {
        if (true === $this->loaded) {
            return;
        }

        $this->initContainer();

        $this->loaded = true;
    }

    /**
     * Initialize container
     *
     * @access public
     *
     * @param string $configPath Path to configure Container
     *
     * @return void
     */
    public function initContainer()
    {
        $this->container = new Container;
        $this->container->kernel = $this;

        $this->container->load($this->getConfig());

        if (false === $this->container->dispatcher) {
            $this->container->dispatcher = new Dispatcher($this->container);
        }
    }

    /**
     * Starting ...
     *
     * @access public
     *
     * @return \Micro\web\IResponse
     * @throws \Micro\base\Exception
     */
    private function doRun()
    {
        $resolver = $this->getResolver();
        $this->container->dispatcher->signal('kernel.router', ['resolver' => $resolver]);

        $app = $resolver->getApplication();
        $this->container->dispatcher->signal('kernel.controller', ['application' => $app]);

        $output = $app->action($resolver->getAction());
        if (!$output instanceof IOutput) {
            $response = $this->container->response ?: new Response;
            $response->setBody($output);
            $output = $response;
        }
        $this->container->dispatcher->signal('kernel.response', ['output' => $output]);

        return $output;
    }

    /**
     * Get resolver
     *
     * @access public
     *
     * @param bool|false $isCli CLI or Web
     *
     * @return ConsoleResolver|HMVCResolver
     */
    public function getResolver()
    {
        if ($this->container->request->isCli()) {
            return new ConsoleResolver($this->container);
        }

        return new HMVCResolver($this->container);
    }

    // Methods for components

    /**
     * Do exception
     *
     * @access private
     *
     * @param \Exception $e Exception
     *
     * @return IOutput
     * @throws \Micro\base\Exception
     */
    private function doException(\Exception $e)
    {
        $this->container->dispatcher->signal('kernel.exception', ['exception' => $e]);

        $output = $this->container->request->isCli() ? new DefaultConsoleCommand([]) : new Response();

        if ($this->container->request->isCli()) {
            $output->data = '"Error #' . $e->getCode() . ' - ' . $e->getMessage() . '"';
            $output->execute();

            return $output;
        }

        if (!$this->container->errorController) {
            $output->setBody('Option `errorController` not configured');

            return $output;
        }
        if (!$this->container->errorAction) {
            $output->setBody('Option `errorAction` not configured');

            return $output;
        }

        $controller = $this->container->errorController;
        $action = $this->container->errorAction;

        $this->container->request->setPost('error', $e);

        /** @var \Micro\mvc\controllers\IController $result */
        $result = new $controller($this->container, false);
        $result = $result->action($action);

        if ($result instanceof IOutput) {
            return $result;
        }

        $output->setBody($result);

        return $output;
    }

    /**
     * Terminate application
     *
     * @access public
     *
     * @return void
     */
    public function terminate()
    {
        $this->container->dispatcher->signal('kernel.terminate', []);

        if ($this->isDebug() && !$this->container->request->isCli()) {
            // Add timer into page
            echo '<div class=debug_timer>', (microtime(true) - $this->getStartTime()), '</div>';
        }

        $this->unloader();
    }

    /**
     * Get start time
     *
     * @access public
     *
     * @return float|null
     */
    public function getStartTime()
    {
        return $this->isDebug() ? $this->startTime : null;
    }

    /**
     * Unloader subsystem
     *
     * @access public
     *
     * @return void
     */
    public function unloader()
    {
        if (false === $this->loaded) {
            return;
        }

        $this->container = null;
        $this->loaded = false;
    }

    // Methods helpers

    /**
     * Get status of debug
     *
     * @access public
     *
     * @return bool
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * Get character set
     *
     * @access public
     *
     * @return string
     */
    public function getCharset()
    {
        return 'UTF-8';
    }

    /**
     * Get environment name
     *
     * @access public
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Get components container
     *
     * @access public
     *
     * @return IContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

    public function getAppDir()
    {
        return Autoload::getAlias('App')[0];
    }
}
