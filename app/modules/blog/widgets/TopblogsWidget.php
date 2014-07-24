<?php

namespace App\modules\blog\widgets;

use Micro\base\MWidget;

class TopblogsWidget extends MWidget
{
	public function init() {}

	public function run() {
		echo $this->render('topblogs');
	}
}