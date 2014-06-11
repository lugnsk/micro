<?php /** MicroCommand */

/**
 * MCommand class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 * @abstract
 */
abstract class MCommand
{
	/** @var array $args arguments for command */
	public $args = array();
	/** @var bool $result status of execute command */
	public $result = false;
	/** @var string $message status message of execute command */
	public $message = '';


	/**
	 * Set arguments class
	 *
	 * @access public
	 * @param array $args
	 * @result void
	 */
	public function __construct($args = array()) {
		$this->args = $args;
	}
	/**
	 * Execute command
	 * @abstract
	 */
	public abstract function execute();
}