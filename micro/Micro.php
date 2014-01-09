<?php

/*
The MIT License (MIT)

Copyright (c) 2013 Oleg Lunegov

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

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
	 * Current URI
	 * @var string $_uri
	 */
	private $_uri;
	/**
	 * Current module dir
	 * @var string $module
	 */
	public $module;
	/**
	 * Current controller
	 * @var MicroController $_controller
	 */
	private $_controller;
	/**
	 * Connection to DataBase
	 */
	 public $db;

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
		// Register config
		$this->config = $config;
		// Register loader
		spl_autoload_register(array('Micro','autoloader'));
	}
	/**
	 * Run application
	 * @return void
	 */
	public function run() {
		// Parsing URI
		$this->_uri = MicroUrlManager::parseUri();

		// Get uriBlocks
		$uriBlocks = explode('/', $this->_uri);
		if ($this->_uri{0} == '/') {
			array_shift($uriBlocks);
		}

		// connect to DB
		if (!empty($this->config['db'])) {
			$this->db = new MicroDbConnection($this->config['db']);
		}

		// Prepare
		$this->module = $this->prepareModules($uriBlocks);
		$this->prepareController($this->module, $uriBlocks);
	}
	/**
	 * Prepare modules
	 * @param &array uriBlocks
	 * @return string
	 */
	private function prepareModules(&$uriBlocks){
		$path = null;
		for ($i = 0; $i < count($uriBlocks); $i++) {
			if (MicroUrlManager::isUsedModules($this->config['AppDir'] . $path , $uriBlocks[$i])) {
				$path .= DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $uriBlocks[$i];
				unset($uriBlocks[$i]);
			} else break;
		}
		return $path;
	}
	/**
	 * Prepare controller
	 * @param string $path
	 * @param &array $uriBlocks
	 * @return void
	 */
	private function prepareController($path, &$uriBlocks) {
		// Get controller
		$controllerBaseDir = $path . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR;
		$controllerName = ($str = ucfirst(current($uriBlocks))) ? $str : 'Default';

		// Check controller
		if (!file_exists($this->config['AppDir'] . $controllerBaseDir . $controllerName . 'Controller.php')) {
			die( 'Controller ' . $controllerName . ' not set.' );
		}

		// Run controller
		require_once $this->config['AppDir'] . $controllerBaseDir . $controllerName . 'Controller.php';
		$controllerName .= 'Controller';
		$this->_controller = new $controllerName();

		// Get action
		$actionName = ($str = next($uriBlocks)) ? $str : $this->_controller->defaultAction;

		// Check action
		if (!method_exists($this->_controller, 'action'.ucfirst($actionName))){
			die( 'Action ' . $actionName . ' not set.' );
		}

		// Run action
		$actionName = 'action' . ucfirst($actionName);
		$this->_controller->$actionName();
	}
	/**
	 * Return controller
	 * @return MicroController
	 */
	public function getController() {
		return $this->_controller;
	}
	/**
	 * Function load files in application
	 * @param  string classname
	 * @param  string path
	 * @return bool   status
	 */
	public static function autoloader($classname, $path = null) {
		if (!$path) {
			$config = Micro::getInstance()->config;
			if (!isset($config['MicroDir']) OR empty($config['MicroDir'])) {
				return false;
			}
			if (isset($config['AppDir']) AND !empty($config['AppDir'])) {
				if (isset($config['import']) AND !empty($config['import'])) {
					$paths = $config['import'];
					foreach ($paths AS $pat) {
						Micro::autoloader($classname, $config['AppDir'] . DIRECTORY_SEPARATOR . $pat);
					}
				}
			}
			$path = $config['MicroDir'];
		}

		if (!file_exists($path)) {
			return false;
		}

		// search in micro dir
		if ($path = self::find($path, $classname.'.php')) {
			include $path;
			return true;
		}
		return false;
	}
	/**
	 * Find file in directory
	 * @param string $dir
	 * @param string #tosearch
	 * @return bool|string
	 */
	public static function find($dir, $tosearch) {
		$files = array_diff( scandir( $dir ), Array( ".", ".." ) );

		foreach( $files as $d ) {
			if( !is_dir($dir."/".$d) ) {
				if ($d == $tosearch)
					return $dir."/".$d;
			} else {
				$res = self::find($dir."/".$d, $tosearch);
				if ($res)
					return $res;
			}
		}
		return false;
	}
}