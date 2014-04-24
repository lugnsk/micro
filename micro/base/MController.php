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
	/** @var boolean $asWidget */
	public $asWidget = false;
	/** @var array $widgetStack */
	private $widgetStack = array();


	/**
	 * Constructor for this class
	 *
	 * @access public
	 */
	public function __construct(){
		if ($module = MRegistry::get('request')->getModules()) {
			$path = Micro::getInstance()->config['AppDir'] . $module .
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
	 * Run action
	 *
	 * @access public
	 * @param string $name
	 * @throws MException method not declared
	 * @return void
	 */
	public function action($name = 'index') {
		$action = 'action' . ucfirst($name);

		if (!method_exists($this, $action)) {
			$action = 'action' . ucfirst($this->defaultAction);

			if (!method_exists($this, $action)) {
				throw new MException('Method ' . $name . ' is not declared.');
			}
		}

		$this->$action();
	}

	/**
	 * Render partial a view
	 *
	 * @param string $view view name
	 * @param array $data
	 * @return string
	 */
	protected function renderPartial($view, $data=array()) {
		$lay = $this->layout;
		$wid = $this->asWidget;

		$this->layout = null;
		$this->asWidget = false;
		$output = $this->render($view, $data);
		$this->layout = $lay;
		$this->asWidget = $wid;

		return $output;
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

		// Get inf of controller
		$appDirectory = Micro::getInstance()->config['AppDir'];
		$className = null;

		if (!$this->asWidget) {
			$module = MRegistry::get('request')->getModules();
		} else {
			$reflector = new ReflectionClass(get_called_class());
			$module = str_replace($appDirectory, '', dirname($reflector->getFileName()));
			unset($reflector);
		}

		if (!$this->asWidget) {
			$className = str_replace('controller', '', strtolower(MRegistry::get('request')->getController()));
		}

		// Calculate path to view
		$path  = $appDirectory . DIRECTORY_SEPARATOR . (($module) ? $module . DIRECTORY_SEPARATOR : null );

		if ($this->asWidget) {
			$path .=  DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . '.php';
		} else {
			$path .= 'views' . DIRECTORY_SEPARATOR . $className . DIRECTORY_SEPARATOR . $view . '.php';
		}

		// Generate layout path
		$layoutPath = ($this->layout) ? $this->getLayoutFile($appDirectory, $module) : null;
		if (!file_exists($layoutPath)) {
			$layoutPath = ($this->layout) ? $this->getLayoutFile($appDirectory, '') : null;
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
	 * @param string $fileName
	 * @param array  $data
	 * @return string
	 */
	protected function renderFile($fileName, $data=array()) {
		$fileNameLang = substr($fileName, 0, -3) . 'ini';
		if (file_exists($fileNameLang)) {
			$lang = new MLanguage($fileNameLang);
		}
		unset($fileNameLang);

		extract($data, EXTR_PREFIX_SAME, 'data');
		ob_start();
		include $fileName;
		return ob_get_clean();
	}
	/**
	 * Get layout path
	 *
	 * @access protected
	 * @param string $baseDir
	 * @param string $module
	 * @return string
	 */
	protected function getLayoutFile($baseDir, $module) {
		$layout = $baseDir . DIRECTORY_SEPARATOR;
		$layout .= ($module) ? $module.DIRECTORY_SEPARATOR : $module;

		$afterPath = 'views' . DIRECTORY_SEPARATOR . 'layouts' .
			DIRECTORY_SEPARATOR . ucfirst($this->layout) . '.php';

		if (!file_exists($layout . $afterPath)) {
			return false;
		}

		return $layout . $afterPath;
	}
	/**
	 * Redirect user to path
	 *
	 * @access public
	 * @param string $path
	 * @return void
	 */
	public function redirect($path) {
		header('Location: '.$path);
		exit();
	}
	// Widgets:
	/**
	 * Render a widget
	 *
	 * @access public
	 * @param string $name
	 * @param array $options
	 * @param bool $capture
	 * @return string
	 * @throws MException
	 */
	public function widget($name, $options = array(), $capture=false) {
		$name = $name.'Widget';

		if (!class_exists($name)) {
			throw new MException('Widget not found.');
		}

		$widget = new $name($options);
		$widget->init();

		if ($capture) {
			ob_start();
			$widget->run();
			return ob_get_clean();
		} else {
			$widget->run();
		}
		unset($widget);
	}
	/**
	 * Start render widget
	 *
	 * @access public
	 * @param $name
	 * @param array $options
	 * @return mixed
	 * @throws MException
	 */
	public function startWidget($name, $options = array()) {
		$name = $name.'Widget';

		if (!class_exists($name)) {
			throw new MException('Widget not found.');
		}

		if (isset($this->widgetStack[$name])) {
			throw new MException('This widget already started!');
		}

		$this->widgetStack[$name] = new $name($options);
		$this->widgetStack[$name]->init();
		return $this->widgetStack[$name];
	}

	/**
	 * End of widget
	 *
	 * @access public
	 * @param string $name
	 * @throws MException
	 */
	public function endWidget($name){
		$name = $name.'Widget';

		if (!class_exists($name) OR !isset($this->widgetStack[$name])) {
			throw new MException('Widget not started.');
		}

		$widget = $this->widgetStack[$name];
		unset($this->widgetStack[$name]);
		$widget->run();
		unset($widget);
	}
}