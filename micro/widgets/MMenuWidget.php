<?php /** MicroMenuWidget */

/**
 * MMenuWidget class file.
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
class MMenuWidget extends MWidget
{
	public $menu = array();
	public $attributes = array();

	public function __construct($items=array(), $attributes=array()) {
		parent::__construct();

		$this->menu = $items;
		$this->attributes = $attributes;
	}
	public function run() {
		echo MHtml::lists($this->menu, $this->attributes);
	}
	public function init(){}
}