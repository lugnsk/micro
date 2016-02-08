<?php

namespace Micro\Db;

use Micro\Base\Exception;

/**
 * Interface for a connections to data bases
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Db
 * @version 1.0
 * @since 1.0
 */
interface IConnection
{
    /**
     * Send RAW query to DB
     *
     * @access public
     *
     * @param string $query raw query to db
     * @param array $params params for query
     * @param int $fetchType fetching type
     * @param string $fetchClass fetching class
     *
     * @return \PDOStatement|array
     * @throws Exception
     */
    public function rawQuery($query = '', array $params = [], $fetchType = \PDO::FETCH_ASSOC, $fetchClass = 'Model');

    /**
     * List database names on this connection
     *
     * @access public
     * @return mixed
     */
    public function listDatabases();

    /**
     * Info of database
     *
     * @access public
     *
     * @param string $dbName database name
     *
     * @return array
     */
    public function infoDatabase($dbName);

    /**
     * Table exists in db
     *
     * @access public
     *
     * @param string $table table name
     *
     * @return bool
     */
    public function tableExists($table);

    /**
     * List tables in db
     *
     * @access public
     * @return array
     */
    public function listTables();

    /**
     * Create a new table
     *
     * @param string $name table name
     * @param array $elements table elements
     * @param string $params table params
     *
     * @return int
     */
    public function createTable($name, array $elements = [], $params = '');

    /**
     * Clear all data from table
     *
     * @access public
     *
     * @param string $name table name
     *
     * @return int
     */
    public function clearTable($name);

    /**
     * Remove table from database
     *
     * @access public
     *
     * @param string $name table name
     *
     * @return mixed
     */
    public function removeTable($name);

    /**
     * Field exists in table
     *
     * @access public
     *
     * @param string $field field name
     * @param string $table table name
     *
     * @return boolean
     */
    public function fieldExists($field, $table);

    /**
     * Get array fields into table
     *
     * @access public
     *
     * @param string $table table name
     *
     * @return array
     */
    public function listFields($table);

    /**
     * Get info of a field
     *
     * @access public
     *
     * @param string $field field name
     * @param string $table table name
     *
     * @return array
     */
    public function fieldInfo($field, $table);

    /**
     * Set current database
     *
     * @access public
     *
     * @param string $dbName database name
     *
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function switchDatabase($dbName);

    /**
     * Insert row into table
     *
     * @access public
     *
     * @param string $table table name
     * @param array $line lines to added
     * @param bool $multi is multi rows
     *
     * @return bool
     */
    public function insert($table, array $line = [], $multi = false);

    /**
     * Update row in table
     *
     * @access public
     *
     * @param string $table table name
     * @param array $elements elements to update
     * @param string $conditions conditions for search
     *
     * @return bool
     */
    public function update($table, array $elements = [], $conditions = '');

    /**
     * Delete row from table
     *
     * @access public
     *
     * @param string $table table name
     * @param string $conditions conditions to search
     * @param array $ph params array
     *
     * @return bool
     */
    public function delete($table, $conditions, array $ph = []);

    /**
     * Exists element in the table by params
     *
     * @access public
     *
     * @param string $table table name
     * @param array $params params array
     *
     * @return bool
     */
    public function exists($table, array $params = []);

    /**
     * Count element in sub-query
     *
     * @access public
     *
     * @param string $subQuery subject query
     * @param string $table table name
     *
     * @return bool|integer
     */
    public function count($subQuery = '', $table = '');
}
