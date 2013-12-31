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
 * MicroController class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license http://opensource.org/licenses/MIT
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class MicroController
{
	public static $module;
	public $layout;
	public $defaultAction = 'index';

	/**
	 * Contructor for this class
	 * @return void
	 */
	public function __construct(){
		// Get module
		if ($module = Micro::getInstance()->module) {
			$path = Micro::getInstance()->config['AppDir'] . DIRECTORY_SEPARATOR . $module .
				DIRECTORY_SEPARATOR . ucfirst(basename($module)) . 'Module.php';

			include $path;
			$path = substr(basename($path), 0, -4);
			self::$module = new $path();
			
		}

		spl_autoload_register(array('MicroController','autoloader'));
	}
	/**
	 * Autoloader classes
	 * @param string $classname
	 */
	public static function autoloader($classname) {
		$micro = Micro::getInstance();
		if (method_exists(self::$module, 'setImport')) {
			foreach (self::$module->setImport() AS $path) {
				$path = DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $path);
				Micro::autoloader($classname, $micro->config['AppDir'] . $path);
			}
		}
	}
	/**
	 * Render view
	 * @param string $view
	 * @param array  $data
	 * @return string $output
	 */
	protected function render($view, $data=array()) {
		if (empty($view)) { return false; }

		// Get info of controller
		$micro = Micro::getInstance();
		$module = $micro->module;
		$cls    = str_replace('controller', '', strtolower(get_class($micro->getController())));

		// Calculate path to view
		$path = $micro->config['AppDir'] . DIRECTORY_SEPARATOR .
			$module . DIRECTORY_SEPARATOR .
			'views' . DIRECTORY_SEPARATOR .
			$cls . DIRECTORY_SEPARATOR . $view . '.php';

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
	 * @param string $basedir
	 * @param string $module
	 * @return string
	 */
	protected function getLayoutFile($baseDir, $module) {
		// end path of layout
		$layout = $baseDir . DIRECTORY_SEPARATOR;
		$layout .= ($module) ? $module.DIRECTORY_SEPARATOR : $module;
		$afterpath = 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . ucfirst($this->layout) . '.php';

		if (!file_exists($layout . $afterpath)) {
			return false;
		}

		return $layout . $afterpath;
	}
}