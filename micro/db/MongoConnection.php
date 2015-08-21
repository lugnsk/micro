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
     * @throws Exception
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
     * @inheritDoc
     */
    public function rawQuery($query = '', array $params = [], $fetchType = \PDO::FETCH_ASSOC, $fetchClass = 'Model')
    {
        // TODO: Implement rawQuery() method.
    }

    /**
     * @inheritDoc
     */
    public function listDatabases()
    {
        return $this->conn->listDBs();
    }

    /**
     * @inheritDoc
     */
    public function infoDatabase($dbName)
    {
        // TODO: Implement infoDatabase() method.
    }

    /**
     * @inheritDoc
     */
    public function tableExists($table)
    {
        return array_search($table, $this->listTables(), true);
    }

    /**
     * @inheritDoc
     */
    public function listTables()
    {
        $this->conn->{$this->dbName}->listCollections();
    }

    /**
     * @inheritDoc
     */
    public function createTable($name, array $elements = [], $params = '')
    {
        $this->conn->{$this->dbName}->createCollection($name, $params);
    }

    /**
     * @inheritDoc
     */
    public function clearTable($name)
    {
        $this->removeTable($name);
    }

    /**
     * @inheritDoc
     */
    public function removeTable($name)
    {
        $this->conn->$name->drop();
    }

    /**
     * @inheritDoc
     */
    public function fieldExists($field, $table)
    {
        // TODO: Implement fieldExists() method.
    }

    /**
     * @inheritDoc
     */
    public function listFields($table)
    {
        // TODO: Implement listFields() method.
    }

    /**
     * @inheritDoc
     */
    public function fieldInfo($field, $table)
    {
        // TODO: Implement fieldInfo() method.
    }

    /**
     * @inheritDoc
     */
    public function switchDatabase($dbName)
    {
        $this->conn->selectDB($dbName);
        $this->dbName = $dbName;
    }

    /**
     * @inheritDoc
     */
    public function insert($table, array $line = [], $multi = false)
    {
        // TODO: Implement insert() method.
    }

    /**
     * @inheritDoc
     */
    public function update($table, array $elements = [], $conditions = '')
    {
        // TODO: Implement update() method.
    }

    /**
     * @inheritDoc
     */
    public function delete($table, $conditions, array $ph = [])
    {
        // TODO: Implement delete() method.
    }

    /**
     * @inheritDoc
     */
    public function exists($table, array $params = [])
    {
        // TODO: Implement exists() method.
    }

    /**
     * @inheritDoc
     */
    public function count($subQuery = '', $table = '')
    {
        // TODO: Implement count() method.
    }
}
