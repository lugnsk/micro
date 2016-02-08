<?php /** RelationsMicro */

namespace Micro\Mvc\Models;

/**
 * Relations class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Mvc\Models
 * @version 1.0
 * @since 1.0
 */
class Relations implements IRelations
{
    /** @var array $data relations setup */
    protected $data;


    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function get($name)
    {
        return !empty($this->data[$name]) ? $this->data[$name] : false;
    }
} 
