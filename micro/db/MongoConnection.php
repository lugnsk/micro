<?php /** MongoDbConnectionMicro */

namespace Micro\db;

use Micro\base\Exception;

/**
 * MongoDB Connection class file.
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
class MongoConnection extends Connection
{
    /** @var \MongoClient $conn Connection to MongoDB */
    public $conn;
    /** @var array $collection lazy load collections */
    protected $collections = [];
    /** @var string $dbName Database name */
    private $dbName;


    /**
     * Construct MongoDB
     *
     * @access public
     *
     * @param array $config configuration array
     *
     * @result void
     * @throws \MongoConnectionException
     * @throws \Micro\base\Exception
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        if (!empty($config['dbname'])) {
            $this->dbName = $config['dbname'];
        } else {
            throw new Exception('MongoDB database name not defined!');
        }

        try {
            if (!empty($config['connectionString'])) {
                $this->conn = new \MongoClient($config['connectionString'], $config['options']);
            } else {
                $this->conn = new \MongoClient;
            }
        } catch (Exception $e) {
            if (!$config['ignoreFail']) {
                throw new Exception('MongoDB error connect to database');
            }
        }
    }

    /**
     * Destruct MongoDB client
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
        // TODO: Implement rawQuery() method.
    }

    /**
     * @inheritdoc
     */
    public function listDatabases()
    {
        return $this->conn->listDBs();
    }

    /**
     * @inheritdoc
     */
    public function infoDatabase($dbName)
    {
        // TODO: Implement infoDatabase() method.
    }

    /**
     * @inheritdoc
     */
    public function tableExists($table)
    {
        return (bool)array_search($table, $this->listTables(), true);
    }

    /**
     * @inheritdoc
     */
    public function listTables()
    {
        $this->conn->{$this->dbName}->listCollections();
    }

    /**
     * @inheritdoc
     */
    public function createTable($name, array $elements = [], $params = '')
    {
        $this->conn->{$this->dbName}->createCollection($name, $params);
    }

    /**
     * @inheritdoc
     */
    public function clearTable($name)
    {
        $this->removeTable($name);
    }

    /**
     * @inheritdoc
     */
    public function removeTable($name)
    {
        $this->conn->$name->drop();
    }

    /**
     * @inheritdoc
     */
    public function fieldExists($field, $table)
    {
        // TODO: Implement fieldExists() method.
    }

    /**
     * @inheritdoc
     */
    public function listFields($table)
    {
        // TODO: Implement listFields() method.
    }

    /**
     * @inheritdoc
     */
    public function fieldInfo($field, $table)
    {
        // TODO: Implement fieldInfo() method.
    }

    /**
     * @inheritdoc
     */
    public function switchDatabase($dbName)
    {
        $this->conn->selectDB($dbName);
        $this->dbName = $dbName;
    }

    /**
     * @inheritdoc
     */
    public function insert($table, array $line = [], $multi = false)
    {
        // TODO: Implement insert() method.
    }

    /**
     * @inheritdoc
     */
    public function update($table, array $elements = [], $conditions = '')
    {
        // TODO: Implement update() method.
    }

    /**
     * @inheritdoc
     */
    public function delete($table, $conditions, array $ph = [])
    {
        // TODO: Implement delete() method.
    }

    /**
     * @inheritdoc
     */
    public function exists($table, array $params = [])
    {
        // TODO: Implement exists() method.
    }

    /**
     * @inheritdoc
     */
    public function count($subQuery = '', $table = '')
    {
        // TODO: Implement count() method.
    }
}
