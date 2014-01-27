<?php

/**
 * MRegistry class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license http://opensource.org/licenses/MIT
 * @package micro
 * @version 1.0
 * @since 1.0
 */
final class MRegistry
{
	/** @var array $_registry */
	protected static $_registry = array();


	/**
	 * Disable construct
	 * @return void
	 */
	protected function __construct() { }
	/**
	 * Disable clone
	 * @return void
	 */
	protected function __clone() { }

	/**
	 * Get registry value
	 * @param string $name
	 * @return mixed
	 */
	public static function get($name) {
		return (isset(self::$_registry[$name])) ? self::$_registry[$name] : null;
	}
	/**
	 * Get registry value
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public static function set($name, $value) {
		self::$_registry[$name] = $value;
	}
	/**
	 * Get all current values
	 */
	public static function getAll() {
		return self::$_registry;
	}
}