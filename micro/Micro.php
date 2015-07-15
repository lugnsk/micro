<?php /** Micro */

namespace Micro;

use Micro\base\Autoload;
use Micro\base\Dispatcher;
use Micro\base\OutputInterface;
use Micro\base\Registry;
use Micro\resolvers\ConsoleResolver;
use Micro\resolvers\HMVCResolver;
use Micro\web\Request;
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
    /** @var bool $debug Debug-mode flag */
    protected $debug = true;
    /** @var float $startTime Time of start framework */
    protected $startTime;
    /** @var bool $loaded Micro loaded flag */
    protected $loaded;
    /** @var Registry $container Registry is a container for components and options */
    protected $container;


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
     * Initialize framework
     *
     * @access public
     *
     * @param string $appDir Application directory
     * @param string $microDir Micro directory
     * @param string $environment Application environment: devel , prod , test
     * @param bool $debug Debug-mode flag
     * @param bool $registerLoader Register default autoloader
     *
     * @result void
     */
    public function __construct($appDir, $microDir, $environment = 'devel', $debug = true, $registerLoader = true)
    {
        $this->appDir = realpath($appDir);
        $this->microDir = realpath($microDir);
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

        Autoload::setAlias('Micro', $microDir);
        Autoload::setAlias('App', $appDir);
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

        if (!file_exists($config['filename']) || empty($config['callable'])) {
            return false;
        }

        require $config['filename'];
        spl_autoload_register($config['callable'], (bool)$config['throw'], (bool)$config['prepend']);

        return true;
    }

    /**
     * Boot Loader
     *
     * @access public
     *
     * @param string $configPath Path to configure Registry
     *
     * @return void
     */
    public function loader($configPath = '/configs/index.php')
    {
        if (true === $this->loaded) {
            return;
        }

        $this->initContainer($configPath);

        $this->loaded = true;
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
     * Initialize container
     *
     * @access public
     *
     * @param string $configPath Path to configure Registry
     *
     * @return void
     */
    public function initContainer($configPath)
    {
        $this->container = new Registry;
        $this->container->kernel = $this;

        $this->container->load($configPath);

        if (!isset($this->container->dispatcher)) {
            $this->container->dispatcher = new Dispatcher($this->container);
        }
    }

    /**
     * Running application
     *
     * @access public
     *
     * @param Request $request Request object
     * @param string $configPath Path to config file
     *
     * @return Response
     */
    public function run(Request $request, $configPath = '/configs/index.php')
    {
        if (!$this->loaded) {
            $this->loader($configPath);
        }
        $this->container->request = $request;

        $resolver = $request->isCli() ? new ConsoleResolver($this->container) : new HMVCResolver($this->container);
        $this->container->dispatcher->signal('kernel.router', ['resolver' => $resolver]);

        $app = $resolver->getApplication();
        $this->container->dispatcher->signal('kernel.controller', ['application' => $app]);

        $output = $app->action($resolver->getAction());
        if (!$output instanceof OutputInterface) {
            $response = $this->container->response ?: new Response;
            $response->setBody($output);
            $output = $response;
        }
        $this->container->dispatcher->signal('kernel.response', ['output' => $output]);

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
            echo '<div class=timer>', (microtime(true) - $this->getStartTime()), '</div>';
        }

        $this->unloader();
    }

    // Methods for components

    public function getCharset()
    {
        return 'UTF-8';
    }

    public function isDebug()
    {
        return $this->debug;
    }

    public function getStartTime()
    {
        return $this->debug ? $this->startTime : null;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getMicroDir()
    {
        return $this->microDir;
    }

    public function getAppDir()
    {
        return $this->appDir;
    }

    public function getCacheDir()
    {
        return $this->appDir . '/cache/' . $this->environment;
    }

    public function getLogDir()
    {
        return $this->appDir . '/logs';
    }
}