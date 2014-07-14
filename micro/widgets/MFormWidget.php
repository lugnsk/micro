<?php /** MicroFormWidget */

/**
 * MFormWidget class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage widgets
 * @version 1.0
 * @since 1.0
 */
class MFormWidget extends MWidget
{
	/** @property string $action */
	public $action = '';
	/** @property string $method */
	public $method = 'GET';
	/** @property string $type */
	public $type = 'text/plain';
	/**
	 * Initialize widget
	 *
	 * @access public
	 * @return MForm
	 */
	public function init() {
		$this->action = ($this->action) ? $this->action : $_SERVER['REQUEST_URI'];
		echo MHtml::beginForm($this->action,$this->method,array('type'=>$this->type));
		return new MForm;
	}
	/**
	 * Running widget
	 *
	 * @access public
	 * @return void
	 */
	public function run() {
		echo MHtml::endForm();
	}
}