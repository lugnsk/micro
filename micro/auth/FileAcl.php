<?php /** MicroFileACL */

namespace Micro\auth;

use Micro\db\Query;

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
    /** @var array $roles configured roles */
    protected $roles;
    /** @var array $perms configured perms */
    protected $perms;
    /** @var array $rolePermsCompare compare of permissions in roles */
    protected $rolePermsCompare;


    /**
     * Configured ACL with files
     *
     * @access public
     * @param array $params configuration array
     * @result void
     */
    public function __construct($params = [])
    {
        parent::__construct($params);

        $roles = (isset($params['roles'])) ? $params['roles'] : [];
        $this->roles = isset($roles['roles']) ? $roles['roles']: [];
        $this->perms = isset($roles['perms']) ? $roles['perms']: [];
        $this->rolePermsCompare = isset($roles['role_perms']) ? $roles['role_perms']: [];
    }

    /**
     * Get assigned elements
     *
     * @access public
     * @param integer $userId user ID
     * @return mixed
     */
    public function assigned($userId)
    {
        $query = new Query;
        $query->select = '*';
        $query->table = 'acl_user';
        $query->addWhere('`user`='.$userId);
        $query->single = false;
        return $query->run();
    }

    /**
     * Get permissions in role
     *
     * @access private
     * @param integer $role role name
     * @return array
     */
    protected function rolePerms($role)
    {
        return $this->rolePermsCompare[$role];
    }

    /**
     * Check user access to permission
     *
     * @access public
     * @param integer $userId user id
     * @param string $permission checked permission
     * @return bool
     */
    public function check($userId, $permission)
    {
        $permissionId = array_search($permission, $this->perms);
        $assigned = $this->assigned($userId);
        if (!$assigned) {
            return false;
        }

        foreach ($assigned AS $assign) {
            if ($assign['perm'] AND $assign['perm']==$permissionId) {
                return true;
            } elseif ($assign['role'] AND in_array($permissionId, $this->rolePerms($assign['role']))) {
                return true;
            }
        }
        return false;
    }
}