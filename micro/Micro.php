<?php /** Micro */

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
class Micro {
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
	private function __clone() {
	}
	/**
	 * Get application singleton instance
	 *
	 * @access public
	 * @param  array $config
	 * @return Micro this
	 */
	public static function getInstance($config = array()) {
		if (self::$_app == null) {
			self::$_app = new Micro($config);
		}

		return self::$_app;
	}
	/**
	 * Constructor application
	 *
	 * @access private
	 * @param array $config
	 */
	private function __construct($config = array()) {
		// Register timer
		$this->timer = explode(" ",microtime());
		$this->timer = $this->timer[1] + $this->timer[0];
		// Register config
		$this->config = $config;
		// Register loader
		require $config['MicroDir'] . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'MAutoload.php';
		spl_autoload_register(array('MAutoload','autoloader'));
	}
	/**
	 * Running application
	 *
	 * @access public
	 * @global MRegistry
	 * @throws MException controller not set
	 * @return void
	 */
	public function run() {
		$path   = $this->prepareController();
		$action = MRegistry::get('request')->getAction();

		require_once $path.'.php';
		$name = basename($path);

		if (!class_exists($name)) {
			throw new MException( 'Controller ' . $name . ' not set' );
		}
		$mvc = new $name;
		$mvc->action($action);

		// Render timer
		if (isset($this->config['timer']) AND $this->config['timer'] == true) {
			$slice = explode(" ",microtime());
			$slice = $slice[1] + $slice[0];
			die( MHtml::openTag('div',array('class'=>'Mruntime')) . ($slice - $this->timer) . MHtml::closeTag('div') );
		}
	}
	/**
	 * Prepare controller to use
	 *
	 * @access private
	 * @global MRegistry
	 * @return string
	 * @throws MException
	 */
	private function prepareController() {
		$request = MRegistry::get('request');
		if (!$request) {
			throw new MException('Component request not loaded.');
		}

		$path = $this->config['AppDir'] . DIRECTORY_SEPARATOR;
		if ($modules = $request->getModules()) {
			$path .= $modules . DIRECTORY_SEPARATOR;
		}
		if ($controller = $request->getController()) {
			$path .= 'controllers' . DIRECTORY_SEPARATOR . $controller;
		}
		if (!file_exists($path . '.php')) {
			throw new MException('File not found in path: ' . $path . '.php');
		}
		return $path;
	}
}