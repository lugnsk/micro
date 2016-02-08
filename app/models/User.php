<?php

namespace App\Models;

use Micro\Mvc\Models\Model;

/**
 * Class User
 * @property string login
 * @property string fio
 * @property string pass
 *
 * @package App
 * @subpackage Models
 */
class User extends Model
{
    static public function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return [
            ['email,login', 'required'],
            ['email', 'email']
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'login' => 'Логин',
            'pass' => 'Пароль',
            'fio' => 'ФИО'

        ];
    }
}
