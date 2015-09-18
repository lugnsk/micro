<?php

namespace Micro\mvc\models;

interface IQuery
{
    /**
     * Add search where
     *
     * @access public
     *
     * @param string $column column name
     * @param string $keyword keyword for search
     * @param boolean $escaped escaping keyword?
     * @param string $operand before added element
     *
     * @return void
     */
    public function addSearch($column, $keyword, $escaped = false, $operand = 'AND');

    /**
     * Add where
     *
     * @access public
     *
     * @param string $sql condition element
     * @param string $operand before added element
     *
     * @return void
     */
    public function addWhere($sql, $operand = 'AND');

    /**
     * Add not search where
     *
     * @access public
     *
     * @param string $column column name
     * @param string $keyword keyword for search
     * @param boolean $escaped escaping keyword?
     * @param string $operand before added element
     *
     * @return void
     */
    public function addNotSearch($column, $keyword, $escaped, $operand = 'AND');

    /**
     * Add in where
     *
     * @access public
     *
     * @param string $column column name
     * @param array|string $params array values or string
     * @param string $operand before added element
     *
     * @return void
     */
    public function addIn($column, $params, $operand = 'AND');

    /**
     * Add not in where
     *
     * @access public
     *
     * @param string $column column name
     * @param array|string $params array values or string
     * @param string $operand before added element
     *
     * @return void
     */
    public function addNotIn($column, $params, $operand = 'AND');

    /**
     * Add between where
     *
     * @access public
     *
     * @param string $column column name
     * @param mixed $start start value
     * @param mixed $stop stop value
     * @param string $operand before added element
     *
     * @return void
     */
    public function addBetween($column, $start, $stop, $operand = 'AND');

    /**
     * Add not between where
     *
     * @access public
     *
     * @param string $column column name
     * @param mixed $start start value
     * @param mixed $stop stop value
     * @param string $operand before added element
     *
     * @return void
     */
    public function addNotBetween($column, $start, $stop, $operand = 'AND');

    /**
     * Add join
     *
     * @access public
     *
     * @param string $table table name
     * @param string $condition condition to search
     * @param string $type type join
     *
     * @return void
     */
    public function addJoin($table, $condition, $type = 'LEFT');

    /**
     * Running this query
     *
     * @access public
     *
     * @param integer $as result as?
     *
     * @return mixed
     * @throws \Micro\base\Exception
     */
    public function run($as = \PDO::FETCH_CLASS);

    /**
     * Generate query string
     *
     * @access public
     * @return string
     */
    public function getQuery();
}
