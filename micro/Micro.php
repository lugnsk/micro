<?php

/**
 * Micro class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license http://opensource.org/licenses/MIT
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class Micro {
	/**
	 * Application singletone
	 * @var Micro $_app
	 */
	protected static $_app;
	/**
	 * Configuration array
	 * @var array $config
	 */
	public $config;
	/**
	 * Timer of generate page
	 */
	private $timer;


	/**
	 * Method CLONE is not alowed for application
	 * @return void
	 */
	private function __clone() {
	}
	/**
	 * Get application singletone instance
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
	 * Contruct application
	 * @return void
	 */
	private function __construct($config = array()) {
		// Register timer
		$this->timer = microtime();
		// Register config
		$this->config = $config;
		// Register loader
		require_once $config['MicroDir'] . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'MAutoload.php';
		spl_autoload_register(array('MAutoload','autoloader'));
	}

	/**
	 * Running application
	 *
	 * @access public
	 * @return void
	 */
	public function run() {
		$this->loadComponents();

		$path   = $this->prepareController();
		$action = MRegistry::get('request')->getAction();

		require_once $path.'.php';
		$name = basename($path);

		if (!class_exists($name)) {
			throw new MException( 'Controller ' . $name . ' not set' );
		}
		$hmvc = new $name($action);

		// Render timer
		if (isset($this->config['timer']) AND $this->config['timer'] == true) {
			$slice = microtime() - $this->timer;
			die( MHtml::openTag('div',array('class'=>'Mruntime')) . $slice . MHtml::closeTag('div') );
		}
	}
	/**
	 * Loading components in Registry
	 *
	 * @access public
	 * @return void
	 */
	private function loadComponents() {
		foreach ($this->config['components'] AS $name => $options) {
			if (!isset($options['class']) OR empty($options['class'])) {
				continue;
			}

			if (!class_exists($options['class'])) {
				continue;
			}

			$classname = $options['class'];
			unset($options['class']);

			MRegistry::set($name, new $classname($options) );
		}
	}
	/**
	 * Prepare controller to use
	 *
	 * @access private
	 * @throw MExction
	 * @return string
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