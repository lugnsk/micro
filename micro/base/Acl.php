<?php

namespace Micro\base;

/**
 * Class Acl
 *
 *
 *
 * @package Micro\base
 */
class Acl {
    public function createList($name, $based=null)
    {
        //
    }
    public function removeList($name)
    {
        //
    }
    public function assignList($userId, $list)
    {
        //
    }
    public function revokeList($userId, $list)
    {
        //
    }
    public function addPermisionToList($permission, $list);
    public function removePermissionFromList($permission, $list);

    public function addedPremissions($list);
    public function assignedUsers($list);

    public function checkAccess($user,$permission);
}