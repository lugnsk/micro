<?php /** MicroDataBaseConnection */

/**
 * MDbConnection class file.
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
class MDbConnection
{
	/** @var PDO|null $conn Connection to DB */
	public $conn;


	/**
	 * Construct for this class
	 *
	 * @access public
	 * @param array $config
	 * @throw MException
	 * @result void
	 */
	public function __construct($config = array()) {
		try {
			$this->conn = new PDO($config['connectionString'], $config['username'], $config['password']);
		} catch (MException $e) {
			die('Подключение к БД не удалось: ' . $e->getMessage());
		}
	}
	/**
	 * Destructor for this class
	 *
	 * @access public
	 * @return void
	 */
	public function __destruct() {
		$this->conn = null;
	}
	/**
	 * List database names on this connecion
	 *
	 * @access public
	 * @return mixed
	 */
	public function listDatabases() {
		$sth = $this->conn->query('SHOW_DATABASES();'); // @TODO: Patch me

		$result = array();
		foreach ($sth->fetchAll() AS $row) {
			$result[] = $row[''];
		}
		return $result;
	}
	/**
	 * List tables in db
	 *
	 * @access public
	 * @return array
	 */
	public function listTables() {
		$sth = $this->conn->query('SHOW TABLES');

		$result = array();
		foreach ($sth->fetchAll() AS $row) {
			$result[] = $row[''];
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
	public function tableExists($table) {
		return (bool)array_search($table, $this->listTables());
	}
	/**
	 * Get array fields into table
	 *
	 * @access public
	 * @param string $table
	 * @return array
	 */
	public function listFields($table) {
		$sth = $this->conn->query('SHOW COLUMNS FROM '.$table.';');
		$sth->setFetchMode(PDO::FETCH_ASSOC);

		$result = array();
		while ($row = $sth->fetch()) {
			$result[] = $row['Field'];
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
	public function fieldExists($field, $table) {
		return (bool)array_search($field, $this->listFields($table));
	}
	// TODO: field_info
	/**
	 * Set current database
	 *
	 * @access public
	 * @param string $dbName
	 * @return boolean
	 */
	public function switchDatabase($dbName) {
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
	public function lastInsertId() {
		return $this->conn->lastInsertId();
	}
}