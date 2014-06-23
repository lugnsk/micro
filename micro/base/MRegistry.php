<?php /** MicroRegistry */

/**
 * MRegistry class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
final class MRegistry
{
	/**
	 * Disable construct
	 *
	 * @access protected
	 * @result void
	 */
	protected function __construct() { }
	/**
	 * Disable clone
	 *
	 * @access protected
	 * @return void
	 */
	protected function __clone() { }
	/**
	 * Get registry value
	 *
	 * @access public
	 * @param string $name
	 * @return mixed
	 */
	public static function get($name) {
		self::configure($name);

		return (isset($GLOBALS[$name])) ? $GLOBALS[$name] : null;
	}
	/**
	 * Get registry value
	 *
	 * @access public
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public static function set($name, $value) {
		self::configure($name);

		$GLOBALS[$name] = $value;
	}
	/**
	 * Get all current values
	 *
	 * @access public
	 * @return array
	 */
	public static function getAll() {
		self::configure();
		return $GLOBALS;
	}
	/**
	 * Get component's
	 *
	 * @access public
	 * @param null $name
	 * @throws MException
	 */
	public static function configure($name=null) {
		if ($name AND isset($GLOBALS[$name])) {
			return; // Already defined
		}
		$configs = Micro::getInstance()->config['components'];

		if ($name AND isset($configs[$name])) {
			if (!self::loadComponent($name, $configs[$name])) {
				throw new MException('Class '.$name.' error loading.');
			}
		} elseif ($name AND !isset($configs[$name])) {
			throw new MException('Class '.$name.' not configured.');
		} else {
			foreach ($configs AS $name => $options) {
				if (!self::loadComponent($name,$options)) {
					throw new MException('Class '.$name.' error loading.');
				}
			}
		}
	}
	/**
	 * Load component
	 *
	 * @access public
	 * @param $name
	 * @param $options
	 * @return bool
	 */
	public static function loadComponent($name, $options) {
		if (!isset($options['class']) OR empty($options['class'])) {
			return false;
		}

		if (!class_exists($options['class'])) {
			return false;
		}

		$className = $options['class'];
		unset($options['class']);

		$GLOBALS[$name] = new $className($options);
		return true;
	}
}