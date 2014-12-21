<?php /** MicroRBAC */

namespace Micro\auth;

use Micro\db\DbConnection;
use Micro\base\Registry;
use Micro\db\Query;

/**
 * Abstract RBAC class file.
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
abstract class Rbac
{
    const TYPE_ROLE = 0;
    const TYPE_PERMISSION = 1;
    const TYPE_OPERATION = 2;

    /** @var DbConnection $conn connection DB */
    protected $conn;

    /**
     * Based constructor for RBAC rules
     *
     * @access public
     * @result void
     */
    public function __construct()
    {
        $this->getConnect();

        if (!$this->conn->tableExists('`rbac_user`')) {
            $this->conn->createTable('`rbac_user`', [
                '`role` varchar(127) NOT NULL',
                '`user` int(10) unsigned NOT NULL',
                'KEY `name` (`name`,`user`)',
            ], 'ENGINE=MyISAM DEFAULT CHARSET=utf8');
        }
    }

    /**
     * Get current connection from registry
     *
     * @access public
     * @result void
     */
    public function getConnect()
    {
        $this->conn = Registry::get('db');
    }

    /**
     * Assign RBAC element into user
     *
     * @access public
     * @param integer $userId user id
     * @param string $name element name
     * @return bool
     */
    abstract public function assign($userId, $name);

    /**
     * Get raw roles
     *
     * @access public
     * @return mixed
     */
    abstract public function rawRoles();

    /**
     * Check privileges to operation
     *
     * @access public
     * @param integer $userId user id
     * @param string $permission permission name
     * @param array $data action params
     * @return boolean
     */
    public function check($userId, $permission, $data = [])
    {
        $tree = $this->tree($this->rawRoles());

        foreach ($this->assigned($userId) AS $role) {
            if ($actionRole = $this->searchRoleRecursive($tree, $role['name'])) {
                if ($trustRole = $this->searchRoleRecursive($actionRole, $permission)) {
                    return $this->execute($trustRole[$permission], $data);
                }
            }
        }
        return false;
    }

    /**
     * Build tree from RBAC rules
     *
     * @access public
     * @param array $elements elemens array
     * @param int $parentId parent ID
     * @return array
     */
    public function tree(&$elements, $parentId = 0)
    {
        $branch = [];
        foreach ($elements AS $key => $element) {
            if ($element['based'] == $parentId) {
                $children = $this->tree($elements, $element['name']);
                if ($children) {
                    $element['childs'] = $children;
                }
                $branch[$element['name']] = $element;
                unset($elements[$key]);
            }
        }
        return $branch;
    }

    /**
     * Execute rule
     *
     * @access public
     * @param array $role element
     * @param array $data action params
     * @return bool
     */
    public function execute($role, $data)
    {
        if (!$role['data']) {
            return true;
        } else {
            extract($data);
            return eval('return ' . $role['data']);
        }
    }

    /**
     * Get assigned to user RBAC elements
     *
     * @access public
     * @param integer $userId user ID
     * @return mixed
     */
    public function assigned($userId)
    {
        $query = new Query;
        $query->distinct = true;
        $query->select = '`role` AS `name`';
        $query->table = '`rbac_user`';
        $query->addWhere('`user`=' . $userId);
        $query->single = false;

        return $query->run(\PDO::FETCH_ASSOC);
    }

    /**
     * Revoke RBAC element from user
     *
     * @access public
     * @param integer $userId user id
     * @param string $name element name
     * @return bool
     */
    public function revoke($userId, $name)
    {
        return $this->conn->delete('rbac_user', 'name=:name AND user=:user', ['name' => $name, 'user' => $userId]);
    }

    /**
     * Recursive search in roles array
     *
     * @access public
     * @param array $roles elements
     * @param string $finder element name to search
     * @return bool|array
     */
    protected function searchRoleRecursive($roles, $finder)
    {
        $result = false;
        foreach ($roles AS $id => $role) {
            if ($id == $finder) {
                $result = [$id => $role];
                break;
            } else {
                if (isset($role['childs']) AND !empty($role['childs'])) {
                    $result = $this->searchRoleRecursive($role['childs'], $finder);
                    break;
                }
            }
        }
        return $result;
    }
}