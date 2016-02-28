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
    /** @var string $appDir Application directory */
    protected $appDir;
    /** @var string $microDir Micro directory */
    protected $microDir;
    /** @var string $webDir Document root */
    protected $webDir;
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
     * @param bool $registerLoader Register default autoloader
     *
     * @result void
     */
    public function __construct($environment = 'devel', $debug = true, $registerLoader = true)
    {
        //$this->appDir = realpath($appDir);
        //$this->microDir = realpath($microDir);
        $this->webDir = getenv('DOCUMENT_ROOT');
        $this->environment = $environment;
        $this->debug = (bool)$debug;
        $this->loaded = false;

        if ($this->debug) {
            $this->startTime = microtime(true);
        }

        if (!$registerLoader) {
            return;
        }

        $this->registerAutoload([
            'filename' => $this->getMicroDir() . '/base/Autoload.php',
            'callable' => ['\Micro\base\Autoload', 'loader']
        ]);

        Autoload::setAlias('Micro', $this->getMicroDir());
        Autoload::setAlias('App', $this->getAppDir());
    }

    /**
     * Register autoload from config array
     *
     * Config format ['filename'=>'' , 'callable'=>'' , 'throw'=>'' , 'prepend'=>''];
     *
     * @access public
     *
     * @param array $config Config array
     *
     * @return bool
     */
    public function registerAutoload(array $config)
    {
        if (empty($config['filename']) || !file_exists($config['filename'])) {
            return false;
        }

        $config = array_merge([
            'filename' => '/autoload.php',
            'callable' => '',
            'throw' => true,
            'prepend' => false
        ], $config);

        if (empty($config['callable']) || !file_exists($config['filename'])) {
            return false;
        }

        /** @noinspection PhpIncludeInspection */
        require $config['filename'];
        spl_autoload_register($config['callable'], (bool)$config['throw'], (bool)$config['prepend']);

        return true;
    }

    /**
     * Git Micro dir
     *
     * @access public
     *
     * @return string
     */
    public function getMicroDir()
    {
        return $this->microDir;
    }

    /**
     * Get application dir
     *
     * @access public
     *
     * @return string
     */
    public function getAppDir()
    {
        return $this->appDir;
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
     * Default config path
     *
     * @return string
     */
    protected function getConfig()
    {
        return '/configs/index.php';
    }

    /**
     * Boot Loader
     *
     * @access public
     *
     * @param string $configPath Path to configure Container
     *
     * @return void
     */
    public function loader()
    {
        if (true === $this->loaded) {
            return;
        }

        $this->initContainer($this->getConfig());

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
    public function initContainer($configPath)
    {
        $this->container = new Container;
        $this->container->kernel = $this;

        $this->container->load($configPath);

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
        $resolver = $this->getResolver($this->container->request->isCli());
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
    public function getResolver($isCli = false)
    {
        if ($isCli) {
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

        if (php_sapi_name() === 'cli') {
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

        if ($this->debug && !$this->container->request->isCli()) {
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
        return $this->debug ? $this->startTime : null;
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

    /**
     * Get web root dir
     *
     * @access public
     *
     * @return string
     */
    public function getWebDir()
    {
        return $this->webDir;
    }

    /**
     * Get cache dir
     *
     * @access public
     *
     * @return string
     */
    public function getCacheDir()
    {
        return $this->appDir . '/cache/' . $this->environment;
    }

    /**
     * Get logs dir
     *
     * @access public
     *
     * @return string
     */
    public function getLogDir()
    {
        return $this->appDir . '/logs';
    }
}
