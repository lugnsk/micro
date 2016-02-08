<?php /** MicroDataBaseConnection */

namespace Micro\Db;

use Micro\Base\Exception;

/**
 * Connection class file.
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
class DbConnection extends Connection
{
    /** @var \PDO|null $conn Connection to DB */
    protected $conn;


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
        parent::__construct($config);

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
        } catch (\PDOException $e) {
            if (!array_key_exists('ignoreFail', $config) || !$config['ignoreFail']) {
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
     * @inheritdoc
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
     * @inheritdoc
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
     * @inheritdoc
     */
    public function infoDatabase($dbName)
    {
        $sth = $this->conn->query("SHOW TABLE STATUS FROM {$dbName};");

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
     * @inheritdoc
     */
    public function tableExists($table)
    {
        return in_array($table, $this->listTables(), false);
    }

    /**
     * @inheritdoc
     */
    public function listTables()
    {
        return $this->conn->query('SHOW TABLES;')->fetchAll(\PDO::FETCH_COLUMN, 0);
    }

    /**
     * @inheritdoc
     */
    public function createTable($name, array $elements = [], $params = '')
    {
        return $this->conn->exec(
            "CREATE TABLE IF NOT EXISTS `{$name}` (`".implode('`,`', $elements)."`) {$params};"
        );
    }

    /**
     * @inheritdoc
     */
    public function clearTable($name)
    {
        return $this->conn->exec("TRUNCATE {$name};");
    }

    /**
     * @inheritdoc
     */
    public function removeTable($name)
    {
        return $this->conn->exec("DROP TABLE {$name};");
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function listFields($table)
    {
        $sth = $this->conn->query("SHOW COLUMNS FROM {$table};");

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
     * @inheritdoc
     */
    public function fieldInfo($field, $table)
    {
        $sth = $this->conn->query("SELECT {$field} FROM {$table} LIMIT 1;");

        return $sth->getColumnMeta(0);
    }

    /**
     * @inheritdoc
     */
    public function switchDatabase($dbName)
    {
        if ($this->conn->exec("USE {$dbName};") !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
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
                    "INSERT INTO {$table} ({$fields}) VALUES ({$values});"
                )->execute($l);
            }
            $id = $dbh ? $this->conn->lastInsertId() : false;
            $this->conn->commit();
        } else {
            $dbh = $this->conn->prepare(
                "INSERT INTO {$table} ({$fields}) VALUES ({$values});"
            )->execute($line);
            $id = $dbh ? $this->conn->lastInsertId() : false;
        }

        return $id ?: false;
    }

    /**
     * @inheritdoc
     */
    public function update($table, array $elements = [], $conditions = '')
    {
        $keys = array_keys($elements);
        if (0 === count($keys)) {
            return false;
        }

        $valStr = [];
        foreach ($keys as $key) {
            $valStr[] = '`' . $key . '` = :' . $key;
        }
        $valStr = implode(',', $valStr);

        if ($conditions) {
            $conditions = 'WHERE ' . $conditions;
        }

        return $this->conn->prepare(
            "UPDATE {$table} SET {$valStr} {$conditions};"
        )->execute($elements);
    }

    /**
     * @inheritdoc
     */
    public function delete($table, $conditions, array $ph = [])
    {
        return $this->conn->prepare(
            "DELETE FROM {$table} WHERE {$conditions};"
        )->execute($ph);
    }

    /**
     * @inheritdoc
     */
    public function exists($table, array $params = [])
    {
        $keys = [];
        foreach ($params AS $key => $val) {
            $keys[] = '`' . $table . '`.`' . $key . '`="' . $val . '"';
        }

        $sth = $this->conn->prepare(
            'SELECT * FROM `' . $table . '` WHERE ' . implode(' AND ', $keys) . ' LIMIT 1;'
        );
        $sth->execute();

        return (bool)$sth->rowCount();
    }

    /**
     * @inheritdoc
     */
    public function count($subQuery = '', $table = '')
    {
        if ($subQuery) {
            $sth = $this->conn->prepare("SELECT COUNT(*) FROM ({$subQuery}) AS m;");
        } elseif ($table) {
            $sth = $this->conn->prepare("SELECT COUNT(*) FROM {$table} AS m;");
        } else {
            return false;
        }
        if ($sth->execute()) {
            return $sth->fetchColumn();
        }

        return false;
    }
}
