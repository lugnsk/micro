<?php

namespace App\modules\blog;

class BlogModule
{
    public static function setImport()
    {
        return [
            'modules.blog.models',
            'modules.blog.widgets'
        ];
    }
}