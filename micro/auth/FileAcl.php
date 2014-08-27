<?php /** MicroFileACL */

namespace Micro\auth;

/**
 * File ACL class file.
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
class FileAcl extends Acl
{
    public $roles=[];

    public function rawRoles()
    {
        return $this->roles;
    }

    public function assignList($userId, $list)
    {
        //
    }
    public function revokeList($userId, $list)
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