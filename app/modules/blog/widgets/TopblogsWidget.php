<?php

class TopblogsWidget extends MWidget
{
	public function init() {}

	public function run() {
		echo $this->render('topblogs');
	}
}