<?php

/**
 * MRequest class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license http://opensource.org/licenses/MIT
 * @package micro
 * @subpackage web
 * @version 1.0
 * @since 1.0
 */
class MRequest
{
	/** @property MRouter $router */
	private $router;

	/** @property string $modules */
	private $modules;
	/** @property string $controller */
	private $controller;
	/** @property string $action */
	private $action;


	/**
	 * Construct Request
	 *
	 * @access public
	 * @param array routes
	 * @return void
	 */
	public function __construct($routes) {
		$this->router = new MRouter($routes);
		$this->initialize();
	}
	/**
	 * Initialize request object
	 *
	 * @access public
	 * return void
	 */
	private function initialize() {
		$uri		= ($_GET['r']) ? $_GET['r'] : '/';
		$trustUri	= $this->router->parse($uri);
		$uriBlocks	= explode('/', $trustUri);

		if ($uri{0} == '/') {
			array_shift($uriBlocks);
		}

		$this->prepareModules($uriBlocks);
		$this->prepareController($uriBlocks);
		$this->prepareAction($uriBlocks);

		if (!empty($uriBlocks)) {
			$uriBlocks = array_values($uriBlocks);

			$gets = array();
			for ($i = 0; $i < count($uriBlocks); $i=$i+2) {
				$gets[$uriBlocks[$i]] = $uriBlocks[$i+1];
			}
			$_GET = array_merge($_GET, $gets);
		}
	}

	/**
	 * Prepare modules
	 */
	private function prepareModules(&$uriBlocks) {
		$path = Micro::getInstance()->config['AppDir'];

		foreach ($uriBlocks AS $i => $block) {
			if (file_exists($path . $this->modules . '/modules/' . $block)) {
				$this->modules .= DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $block;
				unset($uriBlocks[$i]);
			} else break;
		}
	}
	/**
	 * Prepare controller
	 */
	private function prepareController(&$uriBlocks) {
		$this->controller = ($str = array_shift($uriBlocks)) ? $str : 'default';
	}
	/**
	 * Prepare action
	 */
	private function prepareAction(&$uriBlocks) {
		$this->action = ($str = array_shift($uriBlocks)) ? $str : 'index' ;
	}

	/**
	 * Get modules from request
	 *
	 * @access public
	 * @return string
	 */
	public function getModules() {
		return $this->modules;
	}
	/**
	 * Get controller from request
	 *
	 * @access public
	 * @return string
	 */
	public function getController() {
		return ucfirst($this->controller) . 'Controller';
	}
	/**
	 * Get action from request
	 *
	 * @access public
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}
}