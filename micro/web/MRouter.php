<?php

/**
 * MRouter class file.
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
class MRouter
{
	/** @property array $routes */
	public $routes = array(
		'<module:\w+>/<controller:\w+>/<action:\w+>' => '/<module>/<controller>/<action>',
		'<controller:\w+>/<action:\w+>' => '/<controller>/<action>',
	);


	/**
	 * Construct for route scaner
	 *
	 * @param array $routes
	 * @return void
	 */
	public function __construct($routes) {
		$this->routes = array_merge($this->routes, $routes);
	}
	/**
	 * Parsing uri
	 *
	 * @param string $uri
	 * @return string
	 */
	public function parse($uri) {
		// default path
		if ($uri == '/' OR $uri == '') {
			return '/default';
		}

		// scan routes
		foreach ($this->routes AS $condition => $replacement) {
			// slice path
			if ($uri == $condition) {
				return $replacement;
			}
			// pattern path
			if ($validated = $this->validatedRule($uri, $condition, $replacement)) {
				return $validated;
			}
		}
		// return raw uri
		return $uri;
	}
	/**
	 * Validated router rule
	 *
	 * @param string $uri
	 * @param string $condition
	 * @param string $replacement
	 * @return string
	 */
	private function validatedRule($uri, $pattern, $replacement) {
		$uriBlocks = explode('/', $uri);			if ($uriBlocks[0] == '') array_shift($uriBlocks);
		$patBlocks = explode('/', $pattern);		if ($patBlocks[0] == '') array_shift($patBlocks);
		$repBlocks = explode('/', $replacement);	if ($repBlocks[0] == '') array_shift($repBlocks);

		$attr = array(); $result = null;

		if (count($uriBlocks) != count($patBlocks) ) {
			return false;
		}
		if (! $attr = $this->parseUri($uriBlocks, $patBlocks) ) {
			return false;
		}
		$result = $this->buildResult($attr, $repBlocks);
		if ($result == null OR $result == false) {
			return false;
		}

		foreach ($attr AS $key => $val) {
			$_GET[$key] = $val;
		}

		return $result;
	}

	/**
	 * Match patBlocks in uriBlocks
	 *
	 * @access private
	 * @param array $uriBlocks
	 * @param array $patBlocks
	 * @result bool|array
	 */
	private function parseUri($uriBlocks, $patBlocks) {
		$attr = array();

		for ($i = 0; $i < count($uriBlocks); $i++) {
			if ($patBlocks[$i]{0} == '<') {
				$cut = strpos($patBlocks[$i], ':');

				if (preg_match( '/' . substr($patBlocks[$i], $cut+1, -1) . '/' , $uriBlocks[$i])) {
					$attr[ substr($patBlocks[$i], 1, $cut-1) ] = $uriBlocks[$i];
				} else return false;

			} elseif ($uriBlocks[$i] != $patBlocks[$i]) {
				return false;
			}
		}
		return $attr;
	}
	/**
	 * Replacement result with repBlocks and attr
	 *
	 * @access private
	 * @param array $attr
	 * @param array $patBlocks
	 * @result bool|string
	 */
	private function buildResult(&$attr, $repBlocks) {
		$result = null;

		for ($i = 0; $i < count($repBlocks); $i++) {
			if ($repBlocks[$i]{0} == '<') {
				$val = substr($repBlocks[$i], 1, strlen($repBlocks[$i])-1 );

				if (isset($attr[$val])) {
					$result .= '/' . $attr[$val];
					unset($attr[$val]);
				} else return false;

			} else {
				$result .= '/' . $repBlocks[$i];
			}
		}

		return $result;
	}
}
