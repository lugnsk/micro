<?php /** FilterMicro */

namespace Micro\filters;

/**
 * Filter interface file.
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
interface Filter
{
    /**
     * PreFilter
     *
     * @access public
     * @param array $params checked items and other params
     * @return boolean
     */
    public function pre(array $params);

    /**
     * PostFilter
     *
     * @access public
     * @param array $params checked items and other params
     * @return mixed
     */
    public function post(array $params);
}