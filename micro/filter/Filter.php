<?php /** FilterMicro */

namespace Micro\filter;

use Micro\base\Container;

/**
 * Filter class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage filter
 * @version 1.0
 * @since 1.0
 */
abstract class Filter
{
    public $result;
    /** @var Container $Container */
    protected $container;
    protected $action;

    /**
     * @param string $action current action
     * @param Container $Container
     */
    public function __construct($action, Container $Container)
    {
        $this->action = $action;
        $this->container = $Container;
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