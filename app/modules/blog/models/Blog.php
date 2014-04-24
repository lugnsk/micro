<?php

class Blog extends MModel
{
	public $name;
	public $content;

	static public function tableName() {
		return 'blogs';
	}
}