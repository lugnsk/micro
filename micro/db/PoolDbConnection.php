<?php /** PoolDbConnectionMicro */

namespace Micro\db;

use Micro\base\Exception;

/**
 * PoolDbConnection class file.
 *
 * For master-slave's configuration
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage db
 * @version 1.0
 * @since 1.0
 */
class PoolDbConnection
{
    /** @var DbConnection $master master server */
    protected $master;
    /** @var array $servers defined slaves servers */
    protected $servers = [];
    /** @var string $curr current slave server */
    protected $curr;


    /**
     * Make pool of DbConnections
     *
     * If master configuration not defined using first slave server
     *
     * @access public
     *
     * @param array $params params to make
     *
     * @throws \Micro\base\Exception
     */
    public function __construct(array $params = [])
    {
        if (empty($params['servers'])) {
            throw new Exception('Servers not defined');
        }

        if (empty($params['master'])) {
            $params['master'] = $params['servers'][$params['servers'][0]];
        }

        $this->master = new DbConnection($params['master']); // TODO: Fixme

        foreach ($params['servers'] AS $key => $server) {
            $this->servers[$key] = new DbConnection($server, true);
        }

        $this->curr = $this->getCurrentServer();

        if (!$this->curr) {
            $this->curr = $params['servers'][0];
        }
    }

    /**
     * Get current slave server
     *
     * @access protected
     *
     * @return int|string|bool
     */
    protected function getCurrentServer()
    {
        foreach ($this->servers AS $key => $server) {
            if (is_object($server)) {
                return $key;
            }
        }
        return false;
    }

    /**
     * Proxy to good server
     *
     * @access public
     *
     * @param string $name called function
     * @param mixed $args arguments of function
     *
     * @return mixed
     * @throws \Micro\base\Exception
     */
    public function __call($name, $args)
    {
        $curr = $this->servers[$this->curr];

        switch ($name) {
            case 'insert':
            case 'update':
            case 'delete':
            case 'createTable':
            case 'clearTable': {
                $curr = $this->master;
                break;
            }
        }

        if (!method_exists($curr, $name)) {
            throw new Exception('Method not existed into DB');
        }

        return call_user_func_array(array($curr, $name), $args);
    }
}