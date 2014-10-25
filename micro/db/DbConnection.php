<?php /** MicroDataBaseConnection */

namespace Micro\db;

use \Micro\base\Exception;

/**
 * DbConnection class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage db
 * @version 1.0
 * @since 1.0
 */
class DbConnection
{
    /** @var \PDO|null $conn Connection to DB */
    public $conn;


    /**
     * Construct for this class
     *
     * @access public
     * @param array $config configuration array
     * @result void
     * @throw Exception
     */
    public function __construct($config = [])
    {
        try {
            if (!isset($config['options'])) {
                $config['options'] = null;
            }
            $this->conn = new \PDO($config['connectionString'], $config['username'], $config['password'],
                $config['options']);
        } catch (Exception $e) {
            die('Connect to DB failed: ' . $e->getMessage());
        }
    }

    /**
     * Destructor for this class
     *
     * @access public
     * @return void
     */
    public function __destruct()
    {
        $this->conn = null;
    }

    /**
     * Send RAW query to DB
     *
     * @access public
     * @param string $query raw query to db
     * @param array $params params for query
     * @return \PDOStatement
     */
    public function rawQuery($query = '', $params = [])
    {
        $st = $this->conn->query($query);
        $st->execute($params);
        return $st;
    }

    /**
     * List database names on this connection
     *
     * @access public
     * @return mixed
     */
    public function listDatabases()
    {
        $sth = $this->conn->query('SHOW DATABASES;');

        $result = [];
        foreach ($sth->fetchAll() AS $row) {
            $result[] = $row[0];
        }
        return $result;
    }

    /**
     * Info of database
     *
     * @access public
     * @param string $dbName database name
     * @return array
     */
    public function infoDatabase($dbName)
    {
        $sth = $this->conn->query('SHOW TABLE STATUS FROM ' . $dbName . ';');

        $result = [];
        foreach ($sth->fetchAll() AS $row) {
            $result[] = [
                'name' => $row['Name'],
                'engine' => $row['Engine'],
                'rows' => $row['Rows'],
                'length' => $row['Avg_row_length'],
                'increment' => $row['Auto_increment'],
                'collation' => $row['Collation'],
            ];
        }
        return $result;
    }

    /**
     * List tables in db
     *
     * @access public
     * @return array
     */
    public function listTables()
    {
        $sth = $this->conn->query('SHOW TABLES;');

        $result = [];
        foreach ($sth->fetchAll() AS $row) {
            $result[] = $row[0];
        }
        return $result;
    }

    /**
     * Table exists in db
     *
     * @access public
     * @param string $table table name
     * @return bool
     */
    public function tableExists($table)
    {
        return (bool)array_search($table, $this->listTables());
    }

    /**
     * Create a new table
     *
     * @param string $name table name
     * @param array $elements table elements
     * @param string $params table params
     * @return int
     */
    public function createTable($name, $elements = [], $params = '')
    {
        return $this->conn->exec('CREATE TABLE IF NOT EXISTS ' . $name . ' (' . implode(',',
                $elements) . ') ' . $params . ';');
    }

    /**
     * Get array fields into table
     *
     * @access public
     * @param string $table table name
     * @return array
     */
    public function listFields($table)
    {
        $sth = $this->conn->query('SHOW COLUMNS FROM ' . $table . ';');

        $result = [];
        foreach ($sth->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $result[] = [
                'field' => $row['Field'],
                'type' => $row['Type'],
                'null' => $row['Null'],
                'key' => $row['Key'],
                'default' => $row['Default'],
                'extra' => $row['Extra'],
            ];
        }
        return $result;
    }

    /**
     * Field exists in table
     *
     * @access public
     * @param string $field field name
     * @param string $table table name
     * @return boolean
     */
    public function fieldExists($field, $table)
    {
        foreach ($this->listFields($table) AS $tbl) {
            if ($tbl['field'] == $field) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get info of a field
     *
     * @access public
     * @param string $field field name
     * @param string $table table name
     * @return array
     */
    public function fieldInfo($field, $table)
    {
        $sth = $this->conn->query('SELECT ' . $field . ' FROM ' . $table . ' LIMIT 1');
        return $sth->getColumnMeta(0);
    }

    /**
     * Set current database
     *
     * @access public
     * @param string $dbName database name
     * @return boolean
     */
    public function switchDatabase($dbName)
    {
        if ($this->conn->exec('USE ' . $dbName . ';') != false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return last insert row ID
     *
     * @access public
     * @param string $field auto increment field name
     * @return integer
     */
    public function lastInsertId($field = null)
    {
        return $this->conn->lastInsertId($field);
    }

    /**
     * Insert row into table
     *
     * @access public
     * @param string $table table name
     * @param array $line lines to added
     * @return bool
     */
    public function insert($table, $line = [])
    {
        $fields = implode(', ', array_keys($line));
        $values = '"' . implode('", "', array_values($line)) . '"';

        return $this->conn->query(
            'INSERT INTO ' . $table . ' (' . $fields . ') VALUES (' . $values . ');'
        )->execute($line);
    }

    /**
     * Update row in table
     *
     * @access public
     * @param string $table table name
     * @param array $elements elements to update
     * @param string $conditions conditions for search
     * @return bool
     */
    public function update($table, $elements = [], $conditions = '')
    {
        $valStr = [];
        foreach (array_keys($elements) as $key) {
            $valStr[] = '`' . $key . '`=:' . $key;
        }
        if (!empty($conditions)) {
            $conditions = 'WHERE ' . $conditions;
        }

        return $this->conn->query(
            'UPDATE ' . $table . ' SET ' . implode(', ', $valStr) . ' ' . $conditions
        )->execute($elements);
    }

    /**
     * Delete row from table
     *
     * @access public
     * @param string $table table name
     * @param string $conditions conditions to search
     * @param array $ph params array
     * @return bool
     */
    public function delete($table, $conditions, $ph = [])
    {
        return $this->conn->prepare(
            'DELETE FROM ' . $table . ' WHERE ' . $conditions
        )->execute($ph);
    }

    /**
     * Exists element in the table by params
     *
     * @access public
     * @param string $table table name
     * @param array $params params array
     * @return bool
     */
    public function exists($table, $params = [])
    {
        $keys = [];
        foreach ($params AS $key => $val) {
            $keys[] = '`' . $table . '`.`' . $key . '`="' . $val . '"';
        }

        $sth = $this->conn->query(
            'SELECT * FROM ' . $table . ' WHERE ' . implode(' AND ', $keys) . ' LIMIT 1;'
        );
        $sth->execute();

        return (bool)$sth->rowCount();
    }

    /**
     * Count element in sub-query
     *
     * @access public
     * @param string $subQuery subject query
     * @return bool|integer
     */
    public function count($subQuery = '')
    {
        $sth = $this->conn->query('SELECT COUNT(*) FROM (' . $subQuery . ') AS m;');
        if ($sth->execute()) {
            return $sth->fetchColumn();
        }
        return false;
    }
}