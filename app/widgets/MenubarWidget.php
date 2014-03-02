<?php

class MenubarWidget extends MWidget
{
	public function init() {}

	public function run() {
		echo $this->render('menubar');
	}
}