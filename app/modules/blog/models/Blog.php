<?php

namespace App\Modules\Blog\Models;

use Micro\Mvc\Models\Model;

/**
 * Class Blog
 *
 * @property int $id
 *
 * @package App
 * @subpackage Modules\Blog\Models
 */
class Blog extends Model
{
    public $name;
    public $content;

    static public function tableName()
    {
        return 'blogs';
    }
}
