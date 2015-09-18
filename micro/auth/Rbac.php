<?php /** MicroRBAC */

namespace Micro\auth;

use Micro\base\IContainer;
use Micro\mvc\models\Query;

/**
 * Abstract RBAC class file.
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
abstract class Rbac
{
    const TYPE_ROLE = 0;
    const TYPE_PERMISSION = 1;
    const TYPE_OPERATION = 2;

    /** @var IContainer $container */
    protected $container;


    /**
     * Based constructor for RBAC rules
     *
     * @access public
     *
     * @param array $params
     *
     * @result void
     */
    public function __construct(array $params = [])
    {
        $this->container = $params['container'];

        if (!$this->container->db->tableExists('rbac_user')) {
            $this->container->db->createTable('rbac_user', [
                '`role` varchar(127) NOT NULL',
                '`user` int(10) unsigned NOT NULL',
                'UNIQUE KEY `name` (`name`,`user`)'
            ], 'ENGINE=MyISAM DEFAULT CHARSET=utf8');
        }
    }

    /**
     * Assign RBAC element into user
     *
     * @access public
     *
     * @param integer $userId user id
     * @param string $name element name
     *
     * @return bool
     */
    abstract public function assign($userId, $name);

    /**
     * Check privileges to operation
     *
     * @access public
     *
     * @param integer $userId user id
     * @param string $permission permission name
     * @param array $data action params
     *
     * @return boolean
     * @throws \Micro\base\Exception
     */
    public function check($userId, $permission, array $data = [])
    {
        $tree = $this->tree($this->rawRoles());

        /** @var array $roles */
        $roles = $this->assigned($userId);
        if (!$roles) {
            return false;
        }

        foreach ($roles AS $role) {

            $actionRole = $this->searchRoleRecursive($tree, $role['name']);
            if ($actionRole) {
                /** @var array $trustRole */
                $trustRole = $this->searchRoleRecursive($actionRole, $permission);
                if ($trustRole) {
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
     *
     * @param array $elements elements array
     * @param string $parentId parent ID
     *
     * @return array
     */
    public function tree(&$elements, $parentId = '0')
    {
        $branch = [];
        foreach ($elements AS $key => $element) {
            if ($element['based'] === (string)$parentId) {
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
     * Get raw roles
     *
     * @access public
     * @return mixed
     */
    abstract public function rawRoles();

    /**
     * Get assigned to user RBAC elements
     *
     * @access public
     *
     * @param integer $userId user ID
     *
     * @return mixed
     * @throws \Micro\base\Exception
     */
    public function assigned($userId)
    {
        $query = new Query($this->container->db);
        $query->distinct = true;
        $query->select = '`role` AS `name`';
        $query->table = '`rbac_user`';
        $query->addWhere('`user`=' . $userId);
        $query->single = false;

        return $query->run(\PDO::FETCH_ASSOC);
    }

    /**
     * Recursive search in roles array
     *
     * @access public
     *
     * @param array $roles elements
     * @param string $finder element name to search
     *
     * @return bool|array
     */
    protected function searchRoleRecursive($roles, $finder)
    {
        $result = false;
        foreach ($roles AS $id => $role) {
            if ($id === $finder) {
                $result = [$id => $role];
                break;
            } else {
                if (!empty($role['childs'])) {
                    $result = $this->searchRoleRecursive($role['childs'], $finder);
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Execute rule
     *
     * @access public
     *
     * @param array $role element
     * @param array $data action params
     *
     * @return bool
     */
    public function execute(array $role, array $data)
    {
        if (!$role['data']) {
            return true;
        } else {
            extract($data);

            return eval('return ' . $role['data']);
        }
    }

    /**
     * Revoke RBAC element from user
     *
     * @access public
     *
     * @param integer $userId user id
     * @param string $name element name
     *
     * @return bool
     */
    public function revoke($userId, $name)
    {
        return $this->container->db->delete('rbac_user', 'name=:name AND user=:user',
            ['name' => $name, 'user' => $userId]);
    }
}
