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
	/**
	 * @return MForm
	 */
	public function init() {
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