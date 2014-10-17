<?php
/**
 * Created by PhpStorm.
 * User: casper
 * Date: 17.10.14
 * Time: 9:14
 */

namespace Micro\db;


use Micro\base\Exception;

class MongoDbConnection {
    /** @var \MongoClient $conn Connection to MongoDB */
    public $conn;
    /** @var string $dbName Database name */
    private $dbName;
    /** @var array $collection lazy load collections */
    protected $collections=[];

    /**
     * @access protected
     * @param string $collectionName collection name
     * @param boolean $force is a force load
     * @return \MongoCollection
     */
    protected function getCollection($collectionName, $force=false){
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
    public function __construct($config = []){
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
    public function deleteTable($collectionName) {
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
    public function insert($collectionName, $document=[]) {
        return $this->getCollection($collectionName)->insert($document);
    }
    /**
     * Update document
     *
     * @access public
     * @param string $collectionName collection name
     * @param array $conditions
     * @param array $newDocuemnt
     * @param array $options
     * @return bool
     */
    public function update($collectionName, $conditions=[], $newDocuemnt=[], $options=[]) {
        return $this->getCollection($collectionName)->update($conditions, $newDocuemnt, $options);
    }
    /**
     * Delete documents from collection
     * @access public
     * @param string $collectionName collection name
     * @param string|array|null $keys key or keys to dilete
     * @return mixed
     */
    public function delete($collectionName, $keys=null) {
        $collection = $this->getCollection($collectionName);

        if ($keys==null) {
            return $collection->deleteIndexes();
        } else {
            return $collection->deleteIndex($keys);
        }
    }
}