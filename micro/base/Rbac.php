<?php

namespace Micro\base;

// Role | Permisson | Operation
/**
 * Class Rbac
 *
 * При конфигурировании приложения и других обстоятельствах, настраиваем Rbac,
 * А на отдельных итерациях приложения делаем проверки с помощью checkAccess
 *
 * @package Micro
 * @subpackage base
 */
class Rbac {
    const TYPE_ROLE = 0;
    const TYPE_PERMISSION = 1;
    const TYPE_OPERATION = 2;

    /**
     * Функция addElement используется для конфигурирования RBAC правил
     *
     * @param $name
     * @param int $type
     * @param int $based
     * @param null $data
     */
    public function addElement($name, $type = self::TYPE_ROLE, $based=null, $data=null)
    {
        //
    }

    /**
     * Функция assignElement используется для присвоения пользователю правила
     * @param $userId
     * @param $name
     * @param $type
     */
    public function assignElement($userId, $name, $type)
    {
        //
    }

    /**
     * Функция revokeElement используется для снятия правил с пользователя
     * @param $userId
     * @param $name
     * @param $type
     */
    public function revokeElement($userId, $name, $type)
    {
        //
    }

    /**
     * Функция возвращает правило валидации со всеми потомками
     * @param $name
     * @param $type
     */
    public function elementTree($name, $type)
    {
        //
    }

    /**
     * Функция assignedElements возвращает список назначеных пользователю правил
     * @param int $userId
     */
    public function assignedElements($userId)
    {
        //
    }

    /**
     * Функция checkAccess используется для проверки прав
     *
     * @param $userId Идентификатор пользователя
     * @param $action Идентификатор действия
     * @param null $data Дополнительные данные
     */
    public function checkAccess($userId, $action, $data=null)
    {
        //
    }
}