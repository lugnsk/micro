<?php /** FilterMicro */

namespace Micro\filters;

/**
 * Filter class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage filters
 * @version 1.0
 * @since 1.0
 */
abstract class Filter
{
    protected $action;
    public $result;

    /**
     * @param string $action current action
     */
    public function __construct($action) {
        $this->action = $action;
    }

    /**
     * PreFilter
     *
     * @access public
     * @param array $params checked items and other params
     * @return boolean
     */
    abstract public function pre(array $params);

    /**
     * PostFilter
     *
     * @access public
     * @param array $params checked items and other params
     * @return mixed
     */
    abstract public function post(array $params);
}