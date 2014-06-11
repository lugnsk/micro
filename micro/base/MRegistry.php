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
		$GLOBALS[$name] = $value;
	}
	/**
	 * Get all current values
	 *
	 * @access public
	 * @return array
	 */
	public static function getAll() {
		return $GLOBALS;
	}
}