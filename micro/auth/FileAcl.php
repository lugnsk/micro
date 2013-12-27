<?php /** MicroFileACL */

namespace Micro\auth;

use Micro\db\Query;

/**
 * File ACL class file.
 *
 * ACL security with files.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
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
     *
     * @param array $params configuration array
     *
     * @result void
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);

        $roles = !empty($params['roles']) ? $params['roles'] : [];
        $this->roles = !empty($roles['roles']) ? $roles['roles'] : [];
        $this->perms = !empty($roles['perms']) ? $roles['perms'] : [];
        $this->rolePermsCompare = !empty($roles['role_perms']) ? $roles['role_perms'] : [];
    }

    /**
     * Check user access to permission
     *
     * @access public
     *
     * @param integer $userId user id
     * @param string $permission checked permission
     * @param array $data not used, added for compatible!
     *
     * @return bool
     */
    public function check($userId, $permission, array $data = [])
    {
        $permissionId = in_array($permission, $this->perms, true);
        /** @var array $assigned */
        $assigned = $this->assigned($userId);
        if (!$assigned) {
            return false;
        }

        foreach ($assigned AS $assign) {
            if ($assign['perm'] AND $assign['perm'] === $permissionId) {
                return true;
            } elseif ($assign['role'] AND in_array($permissionId, $this->rolePerms($assign['role']), true)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get assigned elements
     *
     * @access public
     *
     * @param integer $userId user ID
     *
     * @return mixed
     */
    public function assigned($userId)
    {
        $query = new Query;
        $query->select = '*';
        $query->table = 'acl_user';
        $query->addWhere('`user`=' . $userId);
        $query->single = false;
        return $query->run();
    }

    /**
     * Get permissions in role
     *
     * @access private
     *
     * @param integer $role role name
     *
     * @return array
     */
    protected function rolePerms($role)
    {
        return $this->rolePermsCompare[$role];
    }
}