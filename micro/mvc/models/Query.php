<?php /** MicroQuery */

namespace Micro\mvc\models;

use Micro\db\IConnection;

/**
 * Query class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage mvc\models
 * @version 1.0
 * @since 1.0
 */
class Query implements IQuery
{
    /** @var IConnection $db Connection */
    public $db;

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
    /** @var integer $offset offset on start result rows */
    public $offset = -1;
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
     *
     * @param IDbConnection $db
     *
     * @result void
     */
    public function __construct(IConnection $db)
    {
        $this->db = $db;
    }

    /**
     * @inheritdoc
     */
    public function addSearch($column, $keyword, $escaped = false, $operand = 'AND')
    {
        $keyword = ($escaped) ? $keyword : '"%' . $keyword . '%"';
        $this->addWhere($column . ' LIKE ' . $keyword, $operand);
    }

    /**
     * @inheritdoc
     */
    public function addWhere($sql, $operand = 'AND')
    {
        $this->where .= ($this->where) ? ' ' . $operand . ' (' . $sql . ')' : ' ' . $this->where . ' (' . $sql . ')';
    }

    /**
     * @inheritdoc
     */
    public function addNotSearch($column, $keyword, $escaped, $operand = 'AND')
    {
        $keyword = ($escaped) ? $keyword : '"%' . $keyword . '%"';
        $this->addWhere($column . ' NOT LIKE ' . $keyword, $operand);
    }

    /**
     * @inheritdoc
     */
    public function addIn($column, $params, $operand = 'AND')
    {
        if (is_array($params)) {
            $params = "'" . implode('\',\'', $params) . '\'';
        }

        $this->addWhere($column . ' IN (' . $params . ')', $operand);
    }

    /**
     * @inheritdoc
     */
    public function addNotIn($column, $params, $operand = 'AND')
    {
        if (is_array($params)) {
            $params = "'" . implode('\',\'', $params) . '\'';
        }

        $this->addWhere($column . ' NOT IN (' . $params . ')', $operand);
    }

    /**
     * @inheritdoc
     */
    public function addBetween($column, $start, $stop, $operand = 'AND')
    {
        $this->addWhere($column . ' BETWEEN ' . $start . ' AND ' . $stop, $operand);
    }

    /**
     * @inheritdoc
     */
    public function addNotBetween($column, $start, $stop, $operand = 'AND')
    {
        $this->addWhere($column . ' BETWEEN ' . $start . ' AND ' . $stop, $operand);
    }

    /**
     * @inheritdoc
     */
    public function addJoin($table, $condition, $type = 'LEFT')
    {
        $this->join .= ' ' . $type . ' JOIN ' . $table . ' ON ' . $condition;
    }

    /**
     * @inheritdoc
     */
    public function run($as = \PDO::FETCH_CLASS)
    {
        $res = $this->db->rawQuery($this->getQuery(), $this->params, $as, $this->objectName);
        if ($this->single) {
            return !empty($res[0]) ? $res[0] : false;
        } else {
            return $res;
        }
    }

    /**
     * @inheritdoc
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

        if ($this->limit !== -1) {
            $query .= ' LIMIT ';

            if ($this->offset !== -1) {
                $query .= $this->offset . ',';
            }

            $query .= $this->limit;
        }

        return $query;
    }
}
