<?php /** MicroQuery */

namespace Micro\db;

use Micro\base\Registry;

/**
 * Query class file.
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
class Query
{
    /** @var DbConnection $conn Current connect to DB */
    public $conn;

    /** @var string $select selectable columns */
    public $select = '*';
    /** @var boolean $distinct unique rows */
    public $distinct = false;
    /** @var string $where condition */
    public $where = '';
    /** @var string $join joins tables */
    public $join = '';
    /** @var string $order sorting result rows */
    public $order = '';
    /** @var string $group grouping result rows */
    public $group = '';
    /** @var string $having condition for result rows */
    public $having = '';
    /** @var integer $limit count result rows */
    public $limit = -1;
    /** @var integer $offset offset on strart result rows */
    public $ofset = -1;
    /** @var array $params masks for where */
    public $params = [];
    /** @var string $table table for query */
    public $table = '';
    /** @var string $objectName class name form fetching */
    public $objectName = '';
    /** @var boolean $single is one result? */
    public $single = false;

    /**
     * Construct class
     *
     * @access public
     * @result void
     */
    public function __construct()
    {
        $this->getDbConnection();
    }

    /**
     * Get connection to db
     *
     * @access public
     * @global Registry
     * @return void
     */
    public function getDbConnection()
    {
        $this->conn = Registry::get('db');
    }

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
    public function addSearch($column, $keyword, $escaped = false, $operand = 'AND')
    {
        $keyword = ($escaped) ? $keyword : '"%' . $keyword . '%"';
        $this->addWhere($column . ' LIKE ' . $keyword, $operand);
    }

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
    public function addWhere($sql, $operand = 'AND')
    {
        $this->where .= ($this->where) ? ' ' . $operand . ' (' . $sql . ')' : ' ' . $this->where . ' (' . $sql . ')';
    }

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
    public function addNotSearch($column, $keyword, $escaped, $operand = 'AND')
    {
        $keyword = ($escaped) ? $keyword : '"%' . $keyword . '%"';
        $this->addWhere($column . ' NOT LIKE ' . $keyword, $operand);
    }

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
    public function addIn($column, $params, $operand = 'AND')
    {
        if (is_array($params)) {
            $params = "'" . implode('\',\'', $params) . '\'';
        }

        $this->addWhere($column . ' IN (' . $params . ')', $operand);
    }

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
    public function addNotIn($column, $params, $operand = 'AND')
    {
        if (is_array($params)) {
            $params = "'" . implode('\',\'', $params) . '\'';
        }

        $this->addWhere($column . ' NOT IN (' . $params . ')', $operand);
    }

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
    public function addBetween($column, $start, $stop, $operand = 'AND')
    {
        $this->addWhere($column . ' BETWEEN ' . $start . ' AND ' . $stop, $operand);
    }

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
    public function addNotBetween($column, $start, $stop, $operand = 'AND')
    {
        $this->addWhere($column . ' BETWEEN ' . $start . ' AND ' . $stop, $operand);
    }

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
    public function addJoin($table, $condition, $type = 'LEFT')
    {
        $this->join .= ' ' . $type . ' JOIN ' . $table . ' ON ' . $condition;
    }

    /**
     * Running this query
     *
     * @access public
     *
     * @param integer $as result as?
     *
     * @return mixed
     */
    public function run($as = \PDO::FETCH_CLASS)
    {
        $res = $this->conn->rawQuery($this->getQuery(), $this->params, $as, $this->objectName);
        if ($this->single) {
            return !empty($res[0]) ? $res[0] : false;
        } else {
            return $res;
        }
    }

    /**
     * Generate query string
     *
     * @access public
     * @return string
     */
    public function getQuery()
    {
        $query = 'SELECT ';
        $query .= ($this->distinct) ? 'DISTINCT ' : '';
        $query .= $this->select . ' FROM ' . $this->table;
        $query .= ($this->join) ? ' ' . $this->join : '';
        $query .= ($this->where) ? ' WHERE ' . $this->where : '';
        $query .= ($this->group) ? ' GROUP BY ' . $this->group : '';
        $query .= ($this->having) ? ' HAVING ' . $this->having : '';
        $query .= ($this->order) ? ' ORDER BY ' . $this->order : '';

        if ($this->limit !== -1 OR $this->ofset !== -1) {
            $query .= ' LIMIT ';

            if ($this->ofset !== -1) {
                $query .= $this->ofset;
            }
            if ($this->limit !== -1 AND $this->ofset !== -1) {
                $query .= ',';
            }
            if ($this->limit !== -1) {
                $query .= $this->limit;
            }
        }
        return $query;
    }
}