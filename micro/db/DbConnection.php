<?php /** MicroDataBaseConnection */

namespace Micro\db;

use Micro\base\Exception;

/**
 * DbConnection class file.
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
class DbConnection
{
    /** @var \PDO|null $conn Connection to DB */
    protected $conn;


    /**
     * Construct for this class
     *
     * @access public
     *
     * @param array $config configuration array
     * @param bool $ignoreFail ignore PDO fail create?
     *
     * @result void
     * @throws Exception
     */
    public function __construct(array $config = [], $ignoreFail = false)
    {
        try {
            if (empty($config['options'])) {
                $config['options'] = null;
            }
            $this->conn = new \PDO($config['connectionString'], $config['username'], $config['password'],
                $config['options']);
        } catch (Exception $e) {
            if (!$ignoreFail) {
               throw new Exception('Connect to DB failed: ' . $e->getMessage());
            }
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
     *
     * @param string $query raw query to db
     * @param array $params params for query
     * @param int $fetchType fetching type
     * @param string $fetchClass fetching class
     *
     * @return \PDOStatement|array
     * @throws Exception
     */
    public function rawQuery($query = '', array $params = [], $fetchType = \PDO::FETCH_ASSOC, $fetchClass = 'Model')
    {
        $sth = $this->conn->prepare($query);

        if ($fetchType === \PDO::FETCH_CLASS) {
            $sth->setFetchMode($fetchType, ucfirst($fetchClass), ['new' => false]);
        } else {
            $sth->setFetchMode($fetchType);
        }

        foreach ($params AS $name => $value) {
            $sth->bindValue($name, $value);
        }
        if ($sth->execute()) {
            return $sth->fetchAll();
        } else {
            throw new Exception($sth->errorCode() . ': ' . print_r($sth->errorInfo()));
        }
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
     *
     * @param string $dbName database name
     *
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
                'collation' => $row['Collation']
            ];
        }
        return $result;
    }

    /**
     * Table exists in db
     *
     * @access public
     *
     * @param string $table table name
     *
     * @return bool
     */
    public function tableExists($table)
    {
        return (bool)in_array($table, $this->listTables(), true);
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
     * Create a new table
     *
     * @param string $name table name
     * @param array $elements table elements
     * @param string $params table params
     *
     * @return int
     */
    public function createTable($name, array $elements = [], $params = '')
    {
        return $this->conn->exec('CREATE TABLE IF NOT EXISTS ' . $name . ' (' . implode(',',
                $elements) . ') ' . $params . ';');
    }

    /**
     * Clear all data from table
     *
     * @access public
     *
     * @param string $name table name
     *
     * @return int
     */
    public function clearTable($name)
    {
        return $this->conn->exec('TRUNCATE `' . $name . '`;');
    }

    /**
     * Remove table from database
     *
     * @access public
     *
     * @param string $name table name
     *
     * @return mixed
     */
    public function removeTable($name)
    {
        return $this->exec('DROP TABLE `' . $name . '`;');
    }

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
    public function fieldExists($field, $table)
    {
        foreach ($this->listFields($table) AS $tbl) {
            if ($tbl['field'] === $field) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get array fields into table
     *
     * @access public
     *
     * @param string $table table name
     *
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
                'extra' => $row['Extra']
            ];
        }
        return $result;
    }

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
    public function fieldInfo($field, $table)
    {
        $sth = $this->conn->query('SELECT ' . $field . ' FROM ' . $table . ' LIMIT 1');
        return $sth->getColumnMeta(0);
    }

    /**
     * Set current database
     *
     * @access public
     *
     * @param string $dbName database name
     *
     * @return boolean
     */
    public function switchDatabase($dbName)
    {
        if ($this->conn->exec('USE ' . $dbName . ';') !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Insert row into table
     *
     * @access public
     *
     * @param string $table table name
     * @param array $line lines to added
     *
     * @return bool
     */
    public function insert($table, array $line = [], $multi = false)
    {
        $fields = implode(', ', array_keys( $multi?$line[0]:$line ));
        $values = ':' . implode(', :', array_keys( $multi?$line[0]:$line )) . '';

        $this->conn->beginTransaction();
        $dbh = $this->conn->prepare(
            'INSERT INTO ' . $table . ' (' . $fields . ') VALUES (' . $values . ');'
        )->execute($line);
        $this->conn->commit();

        if ($dbh) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

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
    public function update($table, array $elements = [], $conditions = '')
    {
        $valStr = [];
        foreach (array_keys($elements) as $key) {
            $valStr[] = '`' . $key . '` = :' . $key;
        }
        if ($conditions) {
            $conditions = 'WHERE ' . $conditions;
        }

        return $this->conn->prepare(
            'UPDATE `' . $table . '` SET ' . implode(', ', $valStr) . ' ' . $conditions
        )->execute($elements);
    }

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
    public function delete($table, $conditions, array $ph = [])
    {
        return $this->conn->prepare(
            'DELETE FROM ' . $table . ' WHERE ' . $conditions
        )->execute($ph);
    }

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
    public function exists($table, array $params = [])
    {
        $keys = [];
        foreach ($params AS $key => $val) {
            $keys[] = '`' . $table . '`.`' . $key . '`="' . $val . '"';
        }

        $sth = $this->conn->prepare(
            'SELECT * FROM ' . $table . ' WHERE ' . implode(' AND ', $keys) . ' LIMIT 1;'
        );
        $sth->execute();

        return (bool)$sth->rowCount();
    }

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
    public function count($subQuery = '', $table = '')
    {
        if ($subQuery) {
            $sth = $this->conn->prepare('SELECT COUNT(*) FROM (' . $subQuery . ') AS m;');
        } elseif ($table) {
            $sth = $this->conn->prepare('SELECT COUNT(*) FROM `' . $table . '` AS m;');
        } else {
            return false;
        }
        if ($sth->execute()) {
            return $sth->fetchColumn();
        }
        return false;
    }
}