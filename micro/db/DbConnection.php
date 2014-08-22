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
    /** @property \PDO|null $conn Connection to DB */
    public $conn;


    /**
     * Construct for this class
     *
     * @access public
     * @param array $config
     * @result void
     * @throw Exception
     */
    public function __construct($config = [])
    {
        try {
            $this->conn = new \PDO($config['connectionString'], $config['username'], $config['password']);
        } catch (Exception $e) {
            die('Подключение к БД не удалось: ' . $e->getMessage());
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
     * @param $dbName
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
     * @param string $table
     * @return bool
     */
    public function tableExists($table)
    {
        return (bool)array_search($table, $this->listTables());
    }

    /**
     * Create a new table
     *
     * @param $name
     * @param array $elements
     * @param string $params
     * @return int
     */
    public function createTable($name, $elements = [], $params = '')
    {
        return $this->conn->exec('CREATE TABLE IF NOT EXISTS ' . $name . ' (' . implode(',', $elements) . ') ' . $params . ';');
    }

    /**
     * Get array fields into table
     *
     * @access public
     * @param string $table
     * @return array
     */
    public function listFields($table)
    {
        $sth = $this->conn->query('SHOW COLUMNS FROM ' . $table . ';');

        $result = [];
        foreach ($sth->fetchAll() as $row) {
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
     * @param string $field
     * @param string $table
     * @return boolean
     */
    public function fieldExists($field, $table)
    {
        return (bool)array_search($field, $this->listFields($table));
    }

    /**
     * Get info of a field
     *
     * @access public
     * @param string $field
     * @param string $table
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
     * @param string $dbName
     * @return boolean
     */
    public function switchDatabase($dbName)
    {
        if ($this->conn->exec('USE ' . $dbName . ';') != FALSE) {
            return true;
        } else return false;
    }

    /**
     * Return last insert row ID
     *
     * @access public
     * @return integer
     */
    public function lastInsertId()
    {
        return $this->conn->lastInsertId();
    }

    /**
     * Insert row into table
     *
     * @access public
     * @param string $table
     * @param array $line
     * @return bool
     */
    public function insert($table, $line=[]) {
        $fields      = implode(', ', array_keys($line));
        $bind_fields = ':' . implode(', :', array_keys($line));

        return $this->conn->query(
            'INSERT INTO ' . $table . ' (' . $fields . ') VALUES (' . $bind_fields . ');'
        )->execute($line);
    }

    /**
     * Update row in table
     *
     * @access public
     * @param string $table
     * @param array $elements
     * @param string $conditions
     * @return bool
     */
    public function update($table, $elements=[], $conditions = '') {
        $valStr = [];
        foreach (array_keys($elements) as $key)
        {
            $valStr[] = '`' . $key . '`=:' . $key;
        }
        if ( ! empty($conditions) ) {
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
     * @param string $table
     * @param string $conditions
     * @param array $ph
     * @return bool
     */
    public function delete($table, $conditions, $ph=[]) {
        return $this->conn->query(
            'DELETE FROM ' . $table . ' WHERE ' . $conditions
        )->execute($ph);
    }

    /**
     * Exists element in the table by params
     *
     * @access public
     * @param string $table
     * @param array $params
     * @return bool
     */
    public function exists($table, $params=[])
    {
        $keys = [];
        foreach ($params AS $key=>$val) {
            $keys[] = $key.'='.$val;
        }
        $sth = $this->conn->query(
            'SELECT * FROM '.$table.' WHERE '.implode(' AND ', $keys).'" LIMIT 1;'
        );
        $sth->execute();

        return (bool)$sth->rowCount();
    }
}