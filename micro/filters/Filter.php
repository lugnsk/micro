<?php /** FilterMicro */

namespace Micro\filters;

use Micro\base\Registry;
use Micro\web\Request;

/**
 * Filter class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage filters
 * @version 1.0
 * @since 1.0
 */
abstract class Filter
{
    /** @var Registry $registry */
    protected $container;

    public $result;
    protected $action;

    /**
     * @param string $action current action
     * @param Registry $registry
     */
    public function __construct($action, Registry $registry )
    {
        $this->action = $action;
        $this->container = $registry;
    }

    /**
     * PreFilter
     *
     * @access public
     *
     * @param array $params checked items and other params
     *
     * @return boolean
     */
    abstract public function pre(array $params);

    /**
     * PostFilter
     *
     * @access public
     *
     * @param array $params checked items and other params
     *
     * @return mixed
     */
    abstract public function post(array $params);
}