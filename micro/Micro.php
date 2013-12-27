<?php /** Micro */

namespace Micro;

use Micro\base\Autoload;
use Micro\base\Console;
use Micro\base\Exception;
use Micro\base\Registry;

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
     *
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
     *
     * @param  array $config configuration array
     *
     * @return Micro this
     * @static
     */
    public static function getInstance(array $config = [])
    {
        if (self::$_app === null) {
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
     * @access protected
     *
     * @param array $config configuration array
     *
     * @result void
     */
    protected function __construct(array $config = [])
    {
        $this->config = $config;

        Autoload::setAlias('Micro', $config['MicroDir']);
        Autoload::setAlias('App', $config['AppDir']);

        spl_autoload_register(['\Micro\base\Autoload', 'loader']);
    }

    /**
     * Running application
     *
     * Launch application as CLI or MVC mode
     *
     * @access public
     *
     * @global Registry
     *
     * @return void
     * @throws Exception controller not set
     */
    public function run()
    {
        if (php_sapi_name() !== 'cli') {
            $this->runMvc();
        } else {
            $this->runCli();
        }
    }

    /**
     * Running command line interface
     *
     * @access protected
     *
     * @global Registry
     *
     * @return void
     * @throws Exception command not set
     */
    protected function runCli()
    {
        global $argv;

        $cli     = new Console($argv);
        $cls     = $cli->getCommand();

        /** @var \Micro\base\Command $command */
        $command = new $cls($cli->getParams());

        $command->execute();

        if (!$command->result) {
            throw new Exception($command->message);
        }
        echo $command->message , "\n";
    }

    /**
     * Running MVC interface
     *
     * @access protected
     *
     * @global Registry
     *
     * @return void
     * @throws Exception
     */
    protected function runMvc()
    {
        $path   = Registry::get('request')->getCalculatePath();
        $action = Registry::get('request')->getAction();

        if (!class_exists($path)) {
            throw new Exception('Controller not found into path `' . $path . '`.');
        }

        /** @var \Micro\mvc\controllers\Controller $mvc ModelViewController */
        $mvc = new $path;
        echo $mvc->action($action);
    }
}