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
    /**
     * @return string
     */
    static public function tableName()
    {
        return 'users';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['email,login', 'required'],
            ['email', 'email']
        ];
    }

    /**
     * @return array
     */
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
