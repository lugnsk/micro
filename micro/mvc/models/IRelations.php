<?php /** MicroInterfaceRelations */

namespace Micro\Mvc\Models;

/**
 * IRelations interface file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Mvc\Models
 * @version 1.0
 * @since 1.0
 * @interface
 */
interface IRelations
{
    /**
     * Setup a relation
     *
     * @access public
     *
     * @param string $name rel name
     * @param bool $isMany One or many results
     * @param string $model Model class for result
     * @param array $on relation types
     * @param string $where options
     * @param array $params arguments
     * @param integer $limit limit rows
     *
     * @return void
     */
    public function add($name, $model, $isMany = false, array $on = [], $where = '', array $params = [], $limit = 0);

    /**
     * Get relation config by name
     *
     * @access public
     *
     * @param string $name
     *
     * @return array
     */
    public function get($name);
}
