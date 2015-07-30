<?php /** MicroMultiDbConnection */

namespace Micro\db;

use Micro\base\Exception;

/**
 * MultiDbConnection class file.
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
class MultiDbConnection
{
    /** @var \Micro\base\IContainer $container Container container */
    protected $container;
    /** @var array $servers Servers into multi system */
    protected $servers;
    /** @var string $curr current server for operations */
    protected $curr;

    /**
     * Constructor multi DB connections
     *
     * @access public
     *
     * @param array $params Servers connections
     *
     * @result void
     * @throws \Micro\base\Exception
     */
    public function __construct(array $params = [])
    {
        $this->container = $params['container'];

        if (empty($params['servers'])) {
            throw new Exception($params['container'], 'Servers not defined');
        }

        foreach ($params['servers'] AS $key => $server) {
            $this->servers[$key] = new DbConnection($server, true);
        }

        $this->curr = key($this->servers);
    }

    /**
     * Proxy calls to current DB
     *
     * @access public
     *
     * @param string $name method name
     * @param array|mixed $args arguments for call
     *
     * @return mixed
     * @throws \Micro\base\Exception
     */
    public function __call($name, $args)
    {
        if (!method_exists($this->servers[$this->curr], $name)) {
            throw new Exception($this->container, 'Method not existed into DB');
        }

        return call_user_func_array(array($this->servers[$this->curr], $name), $args);
    }

    /**
     * DB Switcher
     *
     * @access public
     *
     * @param string $name Set current DB
     *
     * @return void
     */
    public function switchDB($name)
    {
        $this->curr = array_key_exists($name, array_keys($this->servers)) ? $name : $this->servers[0];
    }
}
