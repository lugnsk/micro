<?php

namespace Micro\auth;

use Micro\base\Registry;
use Micro\db\Query;

class DbRbac implements Rbac
{
    /** @var \Micro\db\DbConnection $conn */
    private $conn;


    /**
     * Constructor file RBAC
     *
     * @access public
     * @result void
     */
    public function __construct($params = [])
    {
        $this->getConnect();
        if (!$this->conn->tableExists('`rbac_role`')) {
            $this->conn->createTable('`rbac_role`', [
                '`name` varchar(127) NOT NULL',
                '`type` int(11) NOT NULL DEFAULT \'0\'',
                '`based` varchar(127)',
                '`data` text',
                'UNIQUE KEY `name` (`name`)'
            ], 'ENGINE=MyISAM DEFAULT CHARSET=utf8');
        }
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
     * Add new element into RBAC rules
     *
     * @access public
     * @param $name
     * @param int $type
     * @param string $based
     * @param string $data
     * @return bool
     */
    public function create($name, $type = self::TYPE_ROLE, $based=null, $data=null)
    {
        if ($this->conn->exists('rbac_role', 'name', $name)) {
            return false;
        }

        if (!empty($based) AND !$this->conn->exists('rbac_role', 'name', $based)) {
            return false;
        }

        switch ($type) {
            case Rbac::TYPE_ROLE:
            case Rbac::TYPE_OPERATION:
            case Rbac::TYPE_PERMISSION:
                break;
            default:
                return false;
                break;
        }

        return $this->conn->insert('rbac_role', ['name'=>$name, 'type'=>$type, 'based'=>$based, 'data'=>$data]);
    }

    /**
     * Delete element from RBAC rules
     *
     * @access public
     * @param string $name
     * @result void
     */
    public function delete($name)
    {
        $tree = $this->searchRoleRecursive($this->tree($this->rawRoles()),$name);
        if (!empty($tree)) {
            $this->recursiveDelete($tree, $name);
        }
    }

    public function recursiveDelete(&$tree) {
        foreach ($tree AS $key=>$element) {
            $this->conn->delete('rbac_user', 'role=:name', [ 'name'=>$element['name'] ]);
            $this->conn->delete('rbac_role', 'name=:name', [ 'name'=>$element['name'] ]);

            if (isset($tree['childs'])) {
                $this->recursiveDelete($element['childs']);
            }
            unset($tree[$key]);
        }
    }

    /**
     * Assign RBAC element into user
     *
     * @access public
     * @param integer $userId
     * @param string $name
     * @return bool
     */
    public function assign($userId, $name)
    {
        if ($this->conn->exists('rbac_role', 'name', $name) AND $this->conn->exists('user', 'id', $userId)) {
            return $this->conn->insert('rbac_user', ['name'=>$name, 'user'=>$userId]);
        }
        return false;
    }

    /**
     * Revoke RBAC element from user
     *
     * @access public
     * @param integer $userId
     * @param string $name
     * @return bool
     */
    public function revoke($userId, $name)
    {
        return $this->conn->delete('rbac_user', 'name=:name AND user=:user', ['name'=>$name, 'user'=>$userId]);
    }

    /**
     * Check privileges to operation
     *
     * @access public
     * @param integer $userId
     * @param string $action
     * @param array $data
     * @return boolean
     */
    public function check($userId, $action, $data=[])
    {
        if (!$this->conn->exists('rbac_role', 'name', $action)) {
            return false;
        }

        $tree = $this->tree($this->rawRoles());
        $my = $this->assigned($userId);

        foreach ($this->assigned($userId) AS $role) {
            if ( $actionRole = $this->searchRoleRecursive($tree, $role['name']) ) {
                if ($trustRole = $this->searchRoleRecursive($actionRole, $action)) {
                    return $this->execute($trustRole[$action], $data);
                }
            }
        }
        return false;
    }

    /**
     * Get raw roles
     *
     * @access public
     * @param int $pdo
     * @return mixed
     */
    public function rawRoles($pdo = \PDO::FETCH_ASSOC)
    {
        $query = new Query;
        $query->table = 'rbac_role';
        $query->order = '`type` ASC';
        $query->single = false;
        return $query->run($pdo);
    }

    /**
     * Build tree from RBAC rules
     *
     * @access public
     * @param string $name
     * @return array
     */
    public function tree(&$elements, $parentId = 0)
    {
        $branch = [];
        foreach ($elements AS $key=>$element) {
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
     * Get assigned to user RBAC elements
     *
     * @access public
     * @param integer $userId
     * @return mixed
     */
    public function assigned($userId)
    {
        $query = new Query;
        $query->table = '`rbac_role`';
        $query->addIn('`name`', 'SELECT DISTINCT `role` FROM `rbac_user` WHERE `user`='.$userId);
        $query->single = false;

        return $query->run(\PDO::FETCH_ASSOC);
    }

    /**
     * Recursive search in roles array
     *
     * @access public
     * @param array $roles
     * @param string $finder
     * @return bool|array
     */
    private function searchRoleRecursive($roles, $finder)
    {
        $result = false;
        foreach ($roles AS $id=>$role) {
            if ($id==$finder) {
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

    /**
     * Execute rule
     *
     * @access public
     * @param array $role
     * @param array $data
     * @return bool
     */
    public function execute($role, $data) {
        if (!$role['data']) {
            return true;
        } else {
            extract($data);
            return eval('return ' . $role['data']);
        }
    }
}