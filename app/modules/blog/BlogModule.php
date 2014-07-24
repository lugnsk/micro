<?php

namespace App\modules\blog;

class BlogModule
{
	public static function setImport () {
		return array(
			'modules.blog.models',
			'modules.blog.widgets'
		);
	}
}