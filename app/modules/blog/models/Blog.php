<?php

namespace App\modules\blog\models;

use Micro\db\Model;

/**
 * Class Blog
 * @var int $id
 * @package App\modules\blog\models
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