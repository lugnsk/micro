<?php

/**
 * MCommand class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license http://opensource.org/licenses/MIT
 * @package micro
 * @version 1.0
 * @since 1.0
 */
abstract class MCommand
{
	/***/
	public $args = array();
	/***/
	public $result = false;
	/***/
	public $message = '';

	/**
	 * Set arguments class
	 *
	 * @access public
	 * @param array $args
	 * @return void
	 */
	public function __construct($args = array()) {
		$this->args = $args;
	}
	/**
	 * Execute command
	 *
	 * @access public
	 */
	public abstract function execute();
}