<?php

namespace App\widgets;

use Micro\base\Widget;

class MenubarWidget extends Widget
{
	public function init() {}

	public function run() {
		echo $this->render('menubar');
	}
}