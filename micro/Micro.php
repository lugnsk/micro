<?php /** Micro */

namespace Micro;

use Micro\base\Container;
use Micro\base\Dispatcher;
use Micro\base\IContainer;
use Micro\cli\Consoles\DefaultConsoleCommand;
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

    /** @var IContainer $container Container is a container for components and options */
    protected $container;
    /** @var string $appDir */
    protected $appDir;

    /** @var bool $loaded Micro loaded flag */
    private $loaded;
    /** @var bool $debug Debug-mode flag */
    private $debug = true;
    /** @var string $environment Application environment */
    private $environment = 'devel';
    /** @var float $startTime Time of start framework */
    private $startTime;


    /**
     * Initialize application
     *
     * @access public
     *
     * @param string $environment Application environment: devel , production , test, other
     * @param bool $debug Debug-mode flag
     *
     * @result void
     */
    public function __construct($environment = 'devel', $debug = true)
    {
        $this->environment = (string)$environment;
        $this->debug = (bool)$debug;
        $this->loaded = false;

        ini_set('display_errors', $this->debug);

        /** @TODO: add handler for fatal errors... */

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
        if ($this->debug) { // start new timer
            $this->startTime = microtime(true);
        }

        $this->loaded = false; // deactivate loaded
        $this->container = null; // remove configured container
    }

    /**
     * Running application
     *
     * @access public
     *
     * @param IRequest $request Request object
     *
     * @return Response
     * @throws \Exception
     */
    public function run(IRequest $request)
    {
        if (!$this->loaded) {
            $this->initializeContainer();

            $this->loaded = true;
        }

        $this->container->request = $request;

        $this->container->dispatcher->signal('kernel.request', ['container' => $this->container]);

        try {
            return $this->doRun(); // run application
        } catch (\Exception $e) { // if not caught exception
            if ($this->debug) {
                $this->container->dispatcher->signal('kernel.exception', ['exception' => $e]);
                throw $e;
            }

            return $this->doException($e); // run exception
        }
    }

    /**
     * Initialization container
     *
     * @access protected
     * @return void
     */
    protected function initializeContainer()
    {
        $class = $this->getContainerClass();

        if ($class) {
            $class = new $class;
        }

        $this->container = ($class instanceof IContainer) ? $class : new Container;

        $this->container->kernel = $this;

        $this->container->load($this->getConfig());

        if (false === $this->container->dispatcher) {
            $this->container->dispatcher = new Dispatcher;
        }

        $this->addListener('kernel.kill', function (array $params) {
            if ($params['container']->kernel->isDebug() && !$params['container']->request->isCli()) {
                // Add timer into page
                echo '<div class=debug_timer>', (microtime(true) - $this->getStartTime()), '</div>';
            }
        });

        $this->container->dispatcher->signal('kernel.boot', ['container' => $this->container]);
    }

    /**
     * Get full class name
     * @return string
     */
    protected function getContainerClass()
    {
        return '';
    }

    /**
     * Default config path
     *
     * @return string
     */
    protected function getConfig()
    {
        return $this->getAppDir() . '/configs/index.php';
    }

    /**
     * Get application directory
     *
     * @return string
     */
    public function getAppDir()
    {
        if (!$this->appDir) {
            $this->appDir = dirname((new \ReflectionObject($this))->getFileName());
        }

        return $this->appDir;
    }

    /**
     * Add listener on event
     *
     * @access public
     *
     * @param string $listener listener name
     * @param mixed $event ['Object', 'method'] or callable
     * @param int|null $prior priority
     *
     * @return bool
     */
    protected function addListener($listener, $event, $prior = null)
    {
        if (!is_string($listener) || !$this->container) {
            return false;
        }

        $this->container->dispatcher->addListener($listener, $event, $prior);

        return true;
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
        return $this->startTime;
    }

    /**
     * Starting ...
     *
     * @access private
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
     * @access protected
     *
     * @param bool|false $isCli CLI or Web
     *
     * @return ConsoleResolver|HMVCResolver
     */
    protected function getResolver()
    {
        if ($this->container->request->isCli()) {
            return new ConsoleResolver($this->container);
        }

        return new HMVCResolver($this->container);
    }

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
        $this->container->dispatcher->signal('kernel.kill', ['container' => $this->container]);

        $this->unloader();
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
     * Get logs directory
     *
     * @return string
     */
    public function getLogDir()
    {
        return $this->getAppDir() . '/logs';
    }

    /**
     * Get cache directory
     *
     * @return string
     */
    public function getCacheDir()
    {
        return $this->getAppDir() . '/cache/' . $this->getEnvironment();
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
}
