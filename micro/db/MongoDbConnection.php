<?php /** MongoDbConnectionMicro */

namespace Micro\db;

use Micro\base\Exception;

/**
 * MongoDB Connection class file.
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
class MongoDbConnection
{
    /** @var \MongoClient $conn Connection to MongoDB */
    public $conn;
    /** @var string $dbName Database name */
    private $dbName;
    /** @var array $collection lazy load collections */
    protected $collections = [];

    /**
     * Get collection
     *
     * @access protected
     * @param string $collectionName collection name
     * @param boolean $force is a force load
     * @return \MongoCollection
     */
    protected function getCollection($collectionName, $force = false)
    {
        if ($force) {
            return $this->conn->selectCollection($this->dbName, $collectionName);
        }
        if (!isset($this->collections[$collectionName])) {
            $this->collections[$collectionName] = $this->conn->selectCollection($this->dbName, $collectionName);
        }
        return $this->collections[$collectionName];
    }

    /**
     * Construct MongoDB
     *
     * @access public
     * @param array $config configuration array
     * @result void
     * @throws Exception
     */
    public function __construct(array $config = [])
    {
        if (isset($config['dbname'])) {
            $this->dbName = $config['dbname'];
        } else {
            throw new Exception('MongoDB database name not defined!');
        }

        if (isset($config['connectionString'])) {
            $this->conn = new \MongoClient($config['connectionString'], $config['options']);
        } else {
            $this->conn = new \MongoClient;
        }

        if (!$this->conn instanceof \MongoClient) {
            throw new Exception('MongoDB error connect to database');
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
     * Aggregate
     *
     * @access public
     * @param string $collectionName collection name
     * @param array $params params
     * @param array $options options
     * @return array
     */
    public function aggregate($collectionName, array $params = [], array $options = [])
    {
        return $this->getCollection($collectionName)->aggregate($params, $options);
    }

    /**
     * Add indexes into collection
     *
     * @access public
     * @param string $collectionName collection name
     * @param array $keys indexes
     * @param array $options options
     * @return bool
     */
    public function addIndexes($collectionName, array $keys = [], array $options = [])
    {
        if ($keys) {
            foreach ($keys as $col => $val) {
                if ($val == -1 || $val === false || strtolower($val) == 'desc') {
                    $keys[$col] = -1;
                } else {
                    $keys[$col] = 1;
                }
            }
            return $this->getCollection($collectionName)->ensureIndex($keys, $options);
        }
        return false;
    }

    /**
     * List indexes into collection
     *
     * @access public
     * @param string $collectionName collection name
     * @return array
     */
    public function listIndexes($collectionName)
    {
        return $this->getCollection($collectionName)->getIndexInfo();
    }

    /**
     * Remove index(es) from collection
     *
     * @access public
     * @param string $collectionName collection name
     * @param array $keys indexes
     * @return array
     */
    public function removeIndexes($collectionName, array $keys = [])
    {
        if ($keys) {
            return $this->getCollection($collectionName)->deleteIndex($keys);
        }
        return $this->getCollection($collectionName)->deleteIndexes();
    }

    /**
     * Create reference into collection
     *
     * @access public
     * @param string $collectionName collection name
     * @param string $idObject id object
     * @return array
     */
    public function createReference($collectionName, $idObject)
    {
        return \MongoDBRef::create($collectionName, $idObject, $this->dbName);
    }

    /**
     * Get reference from collection
     *
     * @access public
     * @param \MongoDB $dbObject document object
     * @param array $referenceArray reference array
     * @return array|null
     */
    public function getReference(\MongoDB $dbObject, array $referenceArray)
    {
        return \MongoDBRef::get($dbObject, $referenceArray);
    }

    /**
     * Send raw query
     *
     * @access public
     * @param string $collectionName collection name
     * @param array $params params
     * @param array $fields fields
     * @param bool $single return single document?
     * @return array|\MongoCursor|null
     */
    public function rawQuery($collectionName, array $params = [], array $fields = [], $single = false)
    {
        $collect = $this->getCollection($collectionName);
        return $single ? $collect->findOne($params, $fields) : $collect->find($params, $fields);
    }

    /**
     * List databases into MongoDB server
     *
     * @access public
     * @return array
     */
    public function listDatabases()
    {
        return $this->conn->listDBs();
    }

    /**
     * Delete collection
     *
     * @access public
     * @param string $collectionName collection name
     * @return array
     */
    public function deleteTable($collectionName)
    {
        if (isset($this->collections[$collectionName])) {
            unset($this->collections[$collectionName]);
        }

        return $this->getCollection($collectionName)->drop();
    }

    /**
     * Set current database name
     *
     * @access public
     * @param string $dbName database name
     * @return void
     */
    public function switchDatabase($dbName)
    {
        $this->dbName = $dbName;
    }

    /**
     * Insert document into collection
     *
     * @access public
     * @param string $collectionName collection name
     * @param array $document
     * @return array|bool
     */
    public function insert($collectionName, array $document = [])
    {
        return $this->getCollection($collectionName)->insert($document);
    }

    /**
     * Update document
     *
     * @access public
     * @param string $collectionName collection name
     * @param array $conditions
     * @param array $newDocument
     * @param array $options
     * @return bool
     */
    public function update($collectionName, array $conditions = [], array $newDocument = [], array $options = [])
    {
        return $this->getCollection($collectionName)->update($conditions, $newDocument, $options);
    }

    /**
     * Delete documents from collection
     * @access public
     * @param string $collectionName collection name
     * @param string|array|null $keys key or keys to dilete
     * @return mixed
     */
    public function delete($collectionName, $keys = null)
    {
        $collection = $this->getCollection($collectionName);

        if ($keys == null) {
            return $collection->deleteIndexes();
        } else {
            return $collection->deleteIndex($keys);
        }
    }
}