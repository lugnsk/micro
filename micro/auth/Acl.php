<?php /** MicroACL */

namespace Micro\auth;

/**
 * Abstract ACL class file.
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
abstract class Acl {
    abstract public function createList($name, $based=null);
    abstract public function removeList($name);

    abstract public function assignList($userId, $list);
    abstract public function revokeList($userId, $list);

    abstract public function addPermissionToList($permission, $list);
    abstract public function removePermissionFromList($permission, $list);

    abstract public function addedPermissions($list);
    abstract public function assignedUsers($list);

    abstract public function checkAccess($user,$permission);
}