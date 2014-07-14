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
final class Micro {
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
	protected function __clone() {
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
		$this->timer = microtime(1);
		// Register config
		$this->config = $config;
		// Register loader
		require $config['MicroDir'] . '/base/MAutoload.php';
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
			die(MHtml::openTag('div',array('class'=>'Mruntime')).(microtime(1) - $this->timer).MHtml::closeTag('div'));
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

		$path = $this->config['AppDir'] . '/';
		if ($modules = $request->getModules()) {
			$path .= $modules . '/';
		}
		if ($controller = $request->getController()) {
			$path .= 'controllers/' . $controller;
		}
		if (!file_exists($path . '.php')) {
			throw new MException('File not found in path: ' . $path . '.php');
		}
		return $path;
	}
}