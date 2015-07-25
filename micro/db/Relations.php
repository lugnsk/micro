<?php /** RelationsMicro */

namespace Micro\db;

/**
 * Relations class file.
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
class Relations implements IRelations
{
    /** @var array $data relations setup */
    protected $data;

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
    public function add($name, $model, $isMany = false, array $on = [], $where = '', array $params = [], $limit = 0)
    {
        $this->data[$name] = [
            'IsMany' => $isMany,
            'Model' => $model,
            'On' => $on,
            'Where' => $where,
            'Params' => $params,
            'Limit' => $limit
        ];
    }

    /**
     * Get relation config by name
     *
     * @access public
     *
     * @param string $name
     *
     * @return array
     */
    public function get($name)
    {
        return !empty($this->data[$name]) ? $this->data[$name] : false;
    }
} 