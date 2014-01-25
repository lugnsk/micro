<?php

/**
 * MController class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license http://opensource.org/licenses/MIT
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class MController
{
	/** @var mixed $module */
	public static $module;
	/** @var string $layout */
	public $layout;
	/** @var string $defaultAction */
	public $defaultAction = 'index';

	/**
	 * Contructor for this class
	 * @return void
	 */
	public function __construct(){
		if ($module = MRegistry::get('request')->getModules()) {
			$path = Micro::getInstance()->config['AppDir'] . DIRECTORY_SEPARATOR . $module .
				DIRECTORY_SEPARATOR . ucfirst(basename($module)) . 'Module.php';

			if (file_exists($path)) {
				include $path;
				$path = substr(basename($path), 0, -4);
				self::$module = new $path();
			}
		}

		spl_autoload_register(array('MAutoload','autoloaderController'));
	}
	/**
	 * Render view
	 *
	 * @access protected
	 * @param string $view
	 * @param array  $data
	 * @return string output
	 */
	protected function render($view, $data=array()) {
		if (empty($view)) { return false; }

		// Get info of controller
		$micro		= Micro::getInstance();
		$request	= MRegistry::get('request');

		$module		= $request->getModules();
		$classname	= str_replace('controller', '', strtolower($request->getController()));

		// Calculate path to view
		$path  = $micro->config['AppDir'] . DIRECTORY_SEPARATOR;
		$path .= ($module) ? $module . DIRECTORY_SEPARATOR : null ;

		$path .= 'views' . DIRECTORY_SEPARATOR;
		$path .= ($classname) . DIRECTORY_SEPARATOR . $view . '.php';

		// Generate layout path
		$layoutPath = ($this->layout) ? $this->getLayoutFile($micro->config['AppDir'], $module) : null;
		if (!file_exists($layoutPath)) {
			$layoutPath = ($this->layout) ? $this->getLayoutFile($micro->config['AppDir'], '') : null;
		}

		// Render view
		$output = $this->renderFile($path, $data);
		if ($layoutPath) {
			$output = $this->renderFile($layoutPath, array('content'=>$output));
		}

		return $output;
	}
	/**
	 * Render file by path
	 *
	 * @access protected
	 * @param string $filename
	 * @param array  $data
	 * @return string
	 */
	protected function renderFile($filename, $data=array()) {
		extract($data, EXTR_PREFIX_SAME, 'data');
		ob_start();
		include $filename;
		return ob_get_clean();
	}
	/**
	 * Get layout path
	 *
	 * @access protected
	 * @param string $basedir
	 * @param string $module
	 * @return string
	 */
	protected function getLayoutFile($baseDir, $module) {
		$layout = $baseDir . DIRECTORY_SEPARATOR;
		$layout .= ($module) ? $module.DIRECTORY_SEPARATOR : $module;

		$afterpath = 'views' . DIRECTORY_SEPARATOR . 'layouts' .
			DIRECTORY_SEPARATOR . ucfirst($this->layout) . '.php';

		if (!file_exists($layout . $afterpath)) {
			return false;
		}

		return $layout . $afterpath;
	}
	/**
	 * Redirect user to path
	 *
	 * @access public
	 * @param string path
	 * @return void
	 */
	public function redirect($path) {
		header('Location: '.$path);
		exit();
	}
}