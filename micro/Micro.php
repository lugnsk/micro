<?php /** Micro */

namespace Micro;

use Micro\base\Exception;
use Micro\base\Autoload;
use Micro\base\Registry;
use Micro\web\helpers\Html;

/**
 * Micro class file.
 *
 * Base class for initialize framework
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
final class Micro
{
    /** @var string $version of Micro */
    public static $version = '1.0';
    /** @var Micro $_app Application singleton */
    protected static $_app;
    /** @var array $config Configuration array */
    public $config;
    /** @var integer $timer Timer of generate page */
    private $timer;


    /**
     * Method CLONE is not allowed for application
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
     * @access public
     * @param  array $config configuration array
     * @return Micro this
     */
    public static function getInstance($config = [])
    {
        if (self::$_app == null) {
            self::$_app = new Micro($config);
        }

        return self::$_app;
    }

    /**
     * Constructor application
     *
     * @access private
     * @param array $config configuration array
     * @result void
     */
    private function __construct($config = [])
    {
        // Register timer
        $this->timer = microtime(1);

        // Register config
        $this->config = $config;

        // Register aliases
        Autoload::setAlias('Micro', $config['MicroDir']);
        Autoload::setAlias('App', $config['AppDir']);

        // Patch for composer
        if (isset($config['VendorDir'])) {
            Autoload::setAlias('Vendor', $config['VendorDir']);
        }

        // Register loader
        spl_autoload_register(['\Micro\base\Autoload', 'loader']);
    }

    /**
     * Running application
     *
     * @access public
     * @global Registry
     * @return void
     * @throws Exception controller not set
     */
    public function run()
    {
        $path = $this->prepareController();
        if (!class_exists($path)) {
            if (isset($this->config['errorController']) AND $this->config['errorController']) {
                if (!Autoload::loader($this->config['errorController'])) {
                    throw new Exception('Error controller not valid');
                }
                $path = $this->config['errorController'];
            } else {
                throw new Exception('ErrorController not defined or empty');
            }
        }

        /** @var \Micro\base\Controller $mvc ModelViewController */
        $mvc = new $path;
        $mvc->action(Registry::get('request')->getAction());

        // Render timer
        if (isset($this->config['timer']) AND $this->config['timer'] == true) {
            die(
                Html::openTag('div', ['class' => 'Mruntime']) .
                (microtime(1) - $this->timer) .
                Html::closeTag('div')
            );
        }
    }

    /**
     * Prepare controller to use
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