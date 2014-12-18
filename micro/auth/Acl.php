<?php /** MicroACL */

namespace Micro\auth;

use Micro\base\Registry;
use \Micro\db\DbConnection;

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
abstract class Acl
{
    /** @var string $groupTable name of group table */
    protected $groupTable;
    /** @var DbConnection $conn connection to DB */
    protected $conn;


    /**
     * Base constructor for ACL
     *
     * @access public
     * @param array $params config array
     * @result void
     */
    public function __construct($params = [])
    {
        $this->getConnect();

        if (isset($params['groupTable'])) {
            $this->groupTable = $params['groupTable'];
        }
        if (!$this->conn->tableExists('acl_user')) {
            $this->conn->createTable('acl_user', [
                '`id` int(10) unsigned NOT NULL AUTO_INCREMENT',
                '`user` int(11) unsigned NOT NULL',
                '`role` int(11) unsigned DEFAULT NULL',
                '`perm` int(11) unsigned DEFAULT NULL',
                'PRIMARY KEY (`id`)'
            ], 'ENGINE=MyISAM DEFAULT CHARSET=utf8');
        }
    }

    /**
     * Get current connection from registry
     *
     * @access public
     * @return void
     */
    public function getConnect()
    {
        $this->conn = Registry::get('db');
    }

    /**
     * Get permissions in role
     *
     * @access protected
     * @param string $role role name
     * @return array
     */
    abstract protected function rolePerms($role);

    /**
     * Check user access to permission
     *
     * @access public
     * @param integer $userId user id
     * @param string $permission checked permission
     * @param array $data for compatible, not used!
     * @return bool
     */
    abstract public function check($userId, $permission, $data=[]);
}