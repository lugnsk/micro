<?php /** MicroDbACL */

namespace Micro\auth;

/**
 * Database ACL class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage auth
 * @version 1.0
 * @since 1.0
 */
class DbAcl extends Acl
{
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

    public function addPermissionToList($permission, $list)
    {
        //
    }
    public function removePermissionFromList($permission, $list)
    {
        //
    }

    public function addedPermissions($list)
    {
        //
    }
    public function assignedUsers($list)
    {
        //
    }

    public function checkAccess($user,$permission)
    {
        //
    }
}