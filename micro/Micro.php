<?php /** Micro */

namespace Micro;

use Micro\base\Container;
use Micro\base\Dispatcher;
use Micro\Base\Exception;
use Micro\Base\FatalError;
use Micro\Base\ICommand;
use Micro\base\IContainer;
use Micro\cli\Consoles\DefaultConsoleCommand;
use Micro\Mvc\Controllers\IController;
use Micro\Resolver\IResolver;
use Micro\web\IOutput;
use Micro\web\IRequest;
use Micro\Web\IResponse;
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

        ini_set('display_errors', (integer)$this->debug);

        FatalError::register();

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
        try {
            return $this->doRun($request);
        } catch (\Exception $e) {
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

        $this->container->dispatcher->signal('kernel.boot', ['container' => $this->container]);

        $this->loaded = true;
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
            $this->appDir = realpath(dirname((new \ReflectionObject($this))->getFileName()));
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
     * Send signal to dispatcher
     *
     * @param $signal
     * @param $params
     * @return mixed
     */
    protected function sendSignal($signal, $params)
    {
        return $this->container->dispatcher->signal($signal, $params);
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
     * @param IRequest $request
     *
     * @return Web\IResponse|Response|string
     * @throws \Micro\Base\Exception
     */
    private function doRun(IRequest $request)
    {
        if (!$this->loaded) {
            $this->initializeContainer();

            $this->addListener('kernel.kill', function (array $params) {
                if ($params['container']->kernel->isDebug() && !$params['container']->request->isCli()) {
                    // Add timer into page
                    echo '<div class=debug_timer>', (microtime(true) - $params['container']->kernel->getStartTime()), '</div>';
                }

                if (false === $params['container']->kernel->loaded) {
                    return;
                }

                $params['container']->kernel->container = null;
                $params['container']->kernel->loaded = false;
            });
        }

        $this->container->request = $request;
        if ($output = $this->sendSignal('kernel.request', ['container' => $this->container]) instanceof IResponse) {
            return $output;
        }

        /** @var IResolver $resolver */
        $resolver = $this->getResolver();
        if ($output = $this->sendSignal('kernel.router', ['resolver' => $resolver]) instanceof IResponse) {
            return $output;
        }

        /** @var IController|ICommand $app */
        $app = $resolver->getApplication();
        if ($output = $this->sendSignal('kernel.controller', ['application' => $app]) instanceof IResponse) {
            return $output;
        }

        $output = $app->action($resolver->getAction());
        if (!$output instanceof IOutput) {
            $response = $this->container->response ?: new Response;
            $response->setBody((string)$output);
            $output = $response;
        }

        $this->sendSignal('kernel.response', ['output' => $output]);

        return $output;
    }

    /**
     * Get resolver
     *
     * @access protected
     *
     * @return IResolver
     * @throws \Micro\Base\Exception
     */
    protected function getResolver()
    {
        if ($this->container->request->isCli()) {
            $resolver = $this->container->consoleResolver ?: '\Micro\resolver\ConsoleResolver';
        } else {
            $resolver = $this->container->resolver ?: '\Micro\Resolver\HMVCResolver';
        }

        if (is_string($resolver) && is_subclass_of($resolver, '\Micro\Resolver\IResolver')) {
            $resolver = new $resolver($this->container);
        }

        if (!$resolver instanceof IResolver) {
            throw new Exception('Resolver is not implement an IResolver');
        }

        return $resolver;
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
        if (!$this->container->errorController || !$this->container->errorAction) {
            $output->setBody('Option `errorController` or `errorAction` not configured');

            return $output;
        }
        $this->container->request->setPost('error', $e);

        $controller = $this->container->errorController;

        /** @var \Micro\mvc\controllers\IController $result */
        $result = new $controller($this->container, false);
        $result = $result->action($this->container->errorAction);
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
