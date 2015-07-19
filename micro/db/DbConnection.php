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
class DbConnection implements IDbConnection
{
    /** @var \PDO|null $conn Connection to DB */
    protected $conn;
    /** @var \Micro\base\Container $container Container container */
    protected $container;


    /**
     * Construct for this class
     *
     * @access public
     *
     * @param array $config configuration array
     *
     * @result void
     * @throws Exception
     */
    public function __construct(array $config = [])
    {
        try {
            if (empty($config['options'])) {
                $config['options'] = null;
            }

            $this->conn = new \PDO(
                $config['connectionString'],
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (Exception $e) {
            if (!array_key_exists('ignoreFail', $config) || !$config['ignoreFail']) {
                throw new Exception($config['container'], 'Connect to DB failed: ' . $e->getMessage());
            }
        }

        $this->container = $config['container'];
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
            /** @noinspection PhpMethodParametersCountMismatchInspection */
            $sth->setFetchMode($fetchType, ucfirst($fetchClass), ['container' => $this->container, 'new' => false]);
        } else {
            $sth->setFetchMode($fetchType);
        }

        foreach ($params AS $name => $value) {
            $sth->bindValue($name, $value);
        }

        $sth->execute();

        return $sth->fetchAll();
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
        return in_array($table, $this->listTables(), true);
    }

    /**
     * List tables in db
     *
     * @access public
     * @return array
     */
    public function listTables()
    {
        return $this->conn->query('SHOW TABLES;')->fetchAll(\PDO::FETCH_COLUMN, 0);
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
        return $this->conn->exec('DROP TABLE `' . $name . '`;');
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
     * @param bool $multi is multi rows
     *
     * @return bool
     */
    public function insert($table, array $line = [], $multi = false)
    {
        $fields = '`' . implode('`, `', array_keys($multi ? $line[0] : $line)) . '`';
        $values = ':' . implode(', :', array_keys($multi ? $line[0] : $line));

        $id = null;
        $dbh = null;
        if ($multi) {
            $this->conn->beginTransaction();
            foreach ($line AS $l) {
                $dbh = $this->conn->prepare(
                    'INSERT INTO ' . $table . ' (' . $fields . ') VALUES (' . $values . ');'
                )->execute($l);
            }
            $id = $dbh ? $this->conn->lastInsertId() : false;
            $this->conn->commit();
        } else {
            $dbh = $this->conn->prepare(
                'INSERT INTO ' . $table . ' (' . $fields . ') VALUES (' . $values . ');'
            )->execute($line);
            $id = $dbh ? $this->conn->lastInsertId() : false;
        }

        return $id ?: false;
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
        $keys = array_keys($elements);
        if (!$keys) {
            return false;
        }

        $valStr = [];
        foreach ($keys as $key) {
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