<?php /** Micro */

namespace Micro;

use Micro\base\Exception;
use Micro\base\Autoload;
use Micro\base\Registry;

/**
 * Micro class file.
 *
 * Base class for initialize MicroPHP, used as bootstrap framework.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 * @final
 */
final class Micro
{
    /** @var string $version Version of MicroPHP */
    public static $version = '1.0';
    /** @var Micro $_app Application singleton */
    protected static $_app;
    /** @var array $config Configuration array */
    public $config;


    /**
     * Method CLONE is not allowed for application
     *
     * Clone disabled on MicroPHP base class
     *
     * @access private
     * @return void
     */
    protected function __clone()
    {
    }

    /**
     * Get application singleton instance
     *
     * Getting instance of MicroPHP class
     *
     * @access public
     * @param  array $config configuration array
     * @return Micro this
     * @static
     */
    public static function getInstance(array $config = [])
    {
        if (self::$_app == null) {
            self::$_app = new Micro($config);
        }
        return self::$_app;
    }

    /**
     * Constructor application
     *
     * Private constructor a MicroPHP application.
     * If isset config, application get parameters for initialization
     * and setup components.
     *
     * @access private
     * @param array $config configuration array
     * @result void
     */
    private function __construct(array $config = [])
    {
        $this->config = $config;

        Autoload::setAlias('Micro', $config['MicroDir']);
        Autoload::setAlias('App', $config['AppDir']);
        if (isset($config['VendorDir'])) {
            Autoload::setAlias('Vendor', $config['VendorDir']);
        }

        spl_autoload_register(['\Micro\base\Autoload', 'loader']);
    }

    /**
     * Running application
     *
     * Launch application with defined configs and run node of MVC
     *
     * @access public
     * @global Registry
     * @return void
     * @throws Exception controller not set
     */
    public function run()
    {
        $path = $this->prepareController();
        $action = Registry::get('request')->getAction();

        if (!class_exists($path)) {
            if (isset($this->config['errorController']) AND $this->config['errorController']) {
                if (!Autoload::loader($this->config['errorController'])) {
                    throw new Exception('Error controller not valid');
                }

                $path = $this->config['errorController'];
                $action = isset($config['errorAction']) ? $config['errorAction'] : 'error';
            } else {
                throw new Exception('ErrorController not defined or empty');
            }
        }

        /** @var \Micro\base\Controller $mvc ModelViewController */
        $mvc = new $path;
        $mvc->action($action);
    }

    /**
     * Prepare controller to use
     *
     * Convert request into path to node MVC
     *
     * @access private
     * @global Registry
     * @return string
     * @throws Exception request not loaded
     */
    private function prepareController()
    {
        /** @var \Micro\web\Request $request current request */
        $request = Registry::get('request');
        if (!$request) {
            throw new Exception('Component request not loaded.');
        }

        $path = 'App';
        if ($extensions = $request->getExtensions()) {
            $path .= $extensions;
        }
        if ($modules = $request->getModules()) {
            $path .= $modules;
        }
        if ($controller = $request->getController()) {
            $path .= '\\controllers\\' . $controller;
        }
        return $path;
    }
}