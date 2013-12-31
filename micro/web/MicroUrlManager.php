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
 * MicroUrlManager class file.
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
class MicroUrlManager
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
		$urlManager = new MicroUrlManager();
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
	public function parse($url = '', $params= array()) {
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
			} else {
				$validatedRule .= '/' . $replace;
			}
		}
		return (!empty($validatedRule)) ? $validatedRule : false;
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