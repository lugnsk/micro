<?php

/*
The MIT License (MIT)

Copyright (c) 2013 Oleg Lunegov

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/**
 * MicroQuery class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license http://opensource.org/licenses/MIT
 * @package micro
 * @subpackage db
 * @version 1.0
 * @since 1.0
 */
class MicroQuery
{
	/** @var MicroConnection $_conn Current connect to DB */
	private $_conn;
	/** @var string $select selectable columns */
	public $select		= '*';
	/** @var boolean $distinct uniques rows */
	public $distinct	= false;
	/** @var string $where condition */
	public $where		= '';
	/** @var string $join joins tables */
	public $join		= '';
	/** @var string $order sorting result rows */
	public $order		= '';
	/** @var string $group grouping result rows */
	public $group		= '';
	/** @var string $having condition for result rows */
	public $having		= '';
	/** @var integer $limit count result rows */
	public $limit		= -1;
	/** @var integer $offset offset on strart result rows */
	public $ofset		= -1;
	/** @var array $params masks for where */
	public $params		= array();
	/** @var string $table table for query */
	public $table		= '';
	/** @var boolean $single */
	public $single		= false;

	/**
	 * Construct class
	 * @global Micro connection to db
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->_conn = Micro::getInstance()->db->conn;
	}
	/**
	 * Add where
	 *
	 * @access public
	 * @param string $sql
	 * @param string $operand
	 * @return void
	 */
	public function addWhere($sql, $operand = 'AND') {
		$this->where = ($this->where) ? ' '. $operand. ' (' . $sql . ')' : ' ' . $this->where. ' (' . $sql . ')' ;
	}
	/**
	 * Add search where
	 *
	 * @access public
	 * @param string $column
	 * @param string $keyword
	 * @param boolean $escaped
	 * @param string $operand
	 * @return void
	 */
	public function addSearch($column, $keyword, $escaped = false, $operand = 'AND') {
		$keyword = ($escaped) ? $keyword : '"%'.$keyword.'%"';
		$this->addWhere($column . ' LIKE ' . $keyword ,$operand);
	}
	/**
	 * Add not search where
	 *
	 * @access public
	 * @param string $column
	 * @param string $keyword
	 * @param boolean $escaped
	 * @param string $operand
	 * @return void
	 */
	public function addNotSearch($column, $keyword, $escaped, $operand = 'AND') {
		$keyword = ($escaped) ? $keyword : '"%'.$keyword.'%"';
		$this->addWhere($column . ' NOT LIKE ' . $keyword ,$operand);
	}
	/**
	 * Add in where
	 *
	 * @access public
	 * @param string $column
	 * @param array $arr
	 * @param string $operand
	 * @return void
	 */
	public function addIn($column, $arr=array(), $operand = 'AND') {
		$arr = "('" . implode("','", $arr) . "')";
		$this->addWhere($column . ' IN ' . $arr ,$operand);
	}
	/**
	 * Add not in where
	 *
	 * @access public
	 * @param string $column
	 * @param array $arr
	 * @param string $operand
	 * @return void
	 */
	public function addNotIn($column, $arr=array(), $operand = 'AND') {
		$arr = "('" . implode("','", $arr) . "')";
		$this->addWhere($column . ' NOT IN ' . $arr ,$operand);
	}
	/**
	 * Add between where
	 *
	 * @access public
	 * @param string $column
	 * @param mixed $strart
	 * @param mixed $stop
	 * @param string $operand
	 * @return void
	 */
	public function addBetween($column, $start, $stop, $operand = 'AND') {
		$this->addWhere($column . ' BETWEEN ' . $start . ' AND ' . $stop ,$operand);
	}
	/**
	 * Add not between where
	 *
	 * @access public
	 * @param string $column
	 * @param mixed $strart
	 * @param mixed $stop
	 * @param string $operand
	 * @return void
	 */
	public function addNotBetween($column, $start, $stop, $operand = 'AND') {
		$this->addWhere($column . ' BETWEEN ' . $start . ' AND ' . $stop ,$operand);
	}
	/**
	 * Add join
	 *
	 * @access public
	 * @param string $table
	 * @param string $condition
	 * @param string $type
	 * @return void
	 */
	public function addJoin($table, $cond, $type = 'LEFT') {
		$this->join = ' ' . $type . ' JOIN ' . $table . ' ON ' . $cond;
	}
	/**
	 * Running this query
	 *
	 * @access public
	 * @param boolean $single
	 * @return mixed result's of query
	 */
	public function run($single = false) {
		// generate query string for PDO
		$query = 'SELECT ';
		$query .= ($this->distinct) ? 'DISTINCT ' : '';
		$query .= $this->select . ' FROM ' . $this->table . ' ' .  $this->join . ' WHERE ' . $this->where;
		$query .= ($this->group) ? ' GROUP BY ' . $this->group : '';
		$query .= ($this->having) ? ' HAVING ' . $this->having : '';
		$query .= ($this->order) ? ' ORDER BY ' . $this->order : '';
		if ($this->limit >= 0) {
			$query .= ' LIMIT ';
			if ($this->ofset) {
				$query .= $this->ofset . ',';
			}
			$query .= $this->limit;
		}

		$query = $this->_conn->prepare($query . ';');
		$query->setFetchMode(PDO::FETCH_CLASS, ucfirst($this->table));

		foreach ($this->params AS $name => $value) {
			$query->bindValue($name, $value);
		}

		if ($query->execute()) {
			if ($this->single) {
				return $query->fetch();
			} else {
				return $query->fetchAll();
			}
		}
		return false;
	}
}