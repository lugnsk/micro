<?php /** MicroInterfaceFilter */

namespace Micro\filter;

/**
 * Interface IFilter
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage filter
 * @version 1.0
 * @since 1.0
 *
 * @property array|string $result
 */
interface IFilter
{
    /**
     * PreFilter
     *
     * @access public
     *
     * @param array $params checked items and other params
     *
     * @return boolean
     */
    public function pre(array $params);

    /**
     * PostFilter
     *
     * @access public
     *
     * @param array $params checked items and other params
     *
     * @return mixed
     */
    public function post(array $params);
}
