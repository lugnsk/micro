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
	public $action = '';
	public $method = 'GET';
	public $type = 'text/plain';
	/**
	 * @return MForm
	 */
	public function init() {
		$this->action = ($this->action) ? $this->action : $_SERVER['REQUEST_URI'];
		echo MHtml::beginForm($this->action,$this->method,array('type'=>$this->type));
		return new MForm;
	}

	/**
	 * @return void
	 */
	public function run() {
		echo MHtml::endForm();
	}
}