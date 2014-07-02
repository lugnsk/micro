<?php /** MicroRequest */

/**
 * MRequest class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web
 * @version 1.0
 * @since 1.0
 */
class MRequest
{
	/** @var MRouter $router router for request */
	private $router;
	/** @var string $modules modules in request */
	private $modules;
	/** @var string $controller controller to run */
	private $controller;
	/** @var string $action action to run */
	private $action;


	/**
	 * Constructor Request
	 *
	 * @access public
	 * @param array $routes
	 */
	public function __construct($routes = array()) {
		$this->router = new MRouter($routes['routes']);
		$this->initialize();
	}
	/**
	 * Initialize request object
	 *
	 * @access public
	 * return void
	 */
	private function initialize() {
		$uri		= (isset($_GET['r']) OR !empty($_GET['r'])) ? $_GET['r'] : '/';
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
	 *
	 * @access private
	 * @global Micro
	 * @param array $uriBlocks
	 * @return void
	 */
	private function prepareModules(&$uriBlocks) {
		$path = Micro::getInstance()->config['AppDir'];

		foreach ($uriBlocks AS $i => $block) {
			if (file_exists($path . $this->modules . '/modules/' . $block)) {
				$this->modules .= '/modules/' . $block;
				unset($uriBlocks[$i]);
			} else break;
		}
	}
	/**
	 * Prepare controller
	 *
	 * @access private
	 * @param array $uriBlocks
	 * @return void
	 */
	private function prepareController(&$uriBlocks) {
		$this->controller = ($str = array_shift($uriBlocks)) ? $str : 'default';
	}
	/**
	 * Prepare action
	 *
	 * @access private
	 * @param array $uriBlocks
	 * @return void
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