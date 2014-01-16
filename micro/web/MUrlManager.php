<?php

/**
 * MUrlManager class file.
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
class MUrlManager
{
	private $_defaultRules = array(
		'<module:(\w)+>/<controller:(\w)+>/<action:(\w)+>' => '/<module>/<controller>/<action>',
		'<controller:(\w)+>/<action:(\w)+>' => '/<controller>/<action>',
	);

	/**
	 * Parse URI
	 * @return string
	 */
	public static function parseUri() {
		$config = Micro::getInstance()->config;
		$urlManager = new MUrlManager();

		$cut = ($num = strpos($_SERVER['REQUEST_URI'], '?')) ? $num : strlen($_SERVER['REQUEST_URI']) ;
		$manager = (isset($config['urlManager'])) ? $config['urlManager'] : array();
		$uri = $urlManager->parse(substr($_SERVER['REQUEST_URI'], 0, $cut), $manager);

		// Hack for default page
		if ( empty($uri) OR $uri == '/' ) {
			$uri = '/default';
		}
		return $uri;
	}
	/**
	 * Parse URI
	 * @param  string URI
	 * @param  array  params
	 * @return string
	 */
	public function parse($url = '', $params = array()) {
		// $url is empty
		if (empty($url) OR $url=='/') {
			return $url;
		}
		// Default roles
		$params = array_merge($this->_defaultRules, $params);
		$str = null; 
		// search role
		foreach ($params AS $pattern => $replacement) {
			if (empty($pattern) OR empty($replacement)) {
				continue;
			}
			if ($validated = $this->_validateRule($url, $pattern, $replacement)) {
				$str = $validated;
				break;
			}
		}

		// role not found
		if (empty($str)) {
			$str = $url;
		}
		return $str;
	}
	/**
	 * Validation URI by pattern
	 * @param string URI
	 * @param string pattern
	 * @param string replacement
	 * @return bool|string validated uri
	 */
	private function _validateRule($uri, $pattern, $replacement) {
		if ($uri == $pattern) {
			return $validatedRule = $replacement;
		} elseif ($uri != $pattern AND !strpos('<', $pattern)) {
			return false;
		}

		// Export blocks from parameters
		$patternBlocks     = explode('/', $pattern);
		$uriBlocks         = explode('/', $uri);
		$replacementBlocks = explode('/', $replacement);

		// Huck sizeof
		array_shift($uriBlocks);
		array_shift($replacementBlocks);
		if ($pattern{0} == '/') {
			array_shift($patternBlocks);
		}

		// sums blocks not equal
		if (count($patternBlocks) != count($uriBlocks)) {
			return false;
		}
		$valids = array();
		$validatedRule = null;

		// search pattern blocks in uri
		foreach ($patternBlocks AS $i => $block) {
			if ($block{0} == '<') {
				$elem = substr($block, 1, strpos($block, ':')-1);
				$regexp = '<' . substr($block, strpos($block, ':') + 1, -1) . '>';

				if (preg_match($regexp, $uriBlocks[$i])) {
					$valids['<'.$elem.'>'] = $uriBlocks[$i];
				} else return false;
			}
		}

		foreach ($replacementBlocks AS $i => $replace) {
			if ($replace{0} == '<') {
				if (!isset($valids[$replace])) {
					return false;
				}
				$validatedRule .= '/' . $valids[$replace];
				unset($valids[$replace]);
			} else {
				$validatedRule .= '/' . $replace;
			}
		}

		if (!empty($validatedRule)) {
			foreach ($valids AS $key => $val) {
				$_GET[substr($key, 1, -1)] = $val;
			}
			return $validatedRule;
		}

		return false;
	}
	/**
	 * Is used module's in uri
	 * @param string $directory
	 * @param string $module
	 * @return bool
	 */
	public static function isUsedModules($directory, $module) {
		if (file_exists($directory . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $module)) {
			return true;
		} else return false;
	}
}