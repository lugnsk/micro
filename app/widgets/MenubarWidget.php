<?php

namespace App\widgets;

use Micro\base\MWidget;

class MenubarWidget extends MWidget
{
	public function init() {}

	public function run() {
		echo $this->render('menubar');
	}
}