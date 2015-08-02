<?php

namespace Micro\mvc\models;

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
