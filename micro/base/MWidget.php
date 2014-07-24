<?php /** MicroWidget */

namespace Micro\base;

/**
 * MWidget class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
abstract class MWidget extends \Micro\base\MController
{
	/**
	 * Constructor for widgets
	 *
	 * @access public
	 * @param array $args
	 * @result void
	 */
	public function __construct($args = []) {
		foreach ($args AS $name => $value) {
			$this->$name = $value;
		}
		$this->asWidget = true;
	}
	/**
	 * Initialize widget
	 * @abstract
	 */
	abstract public function init();
	/**
	 * Run widget
	 * @abstract
	 */
	abstract public function run();
}