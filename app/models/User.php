<?php

namespace App\models;

use Micro\db\Model;

class User extends Model
{
    public function rules()
    {
        return [
            ['email,login', 'required']
        ];
    }
    public function attributeLabels()
    {
        return [
            'email'=>'E-mail',
            'login'=>'Логин',
            'pass'=>'Пароль',
            'fio'=>'ФИО',

        ];
    }
    static public function tableName()
    {
        return 'users';
    }
}