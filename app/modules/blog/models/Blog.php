<?php

namespace App\modules\blog\models;

use Micro\db\Model;

/**
 * Class Blog
 *
 * @property int $id
 *
 * @package App
 * @subpackage modules\blog\models
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