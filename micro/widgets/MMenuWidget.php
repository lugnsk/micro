<?php

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