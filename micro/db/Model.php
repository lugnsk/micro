<?php /** MicroModel */

namespace Micro\db;

use Micro\web\FormModel;
use Micro\base\Registry;
use Micro\base\Exception AS MException;

/**
 * Get public vars into object
 *
 * @access public
 * @param mixed $object
 * @return array
 */
function getVars($object) {
	return get_object_vars($object);
}

/**
 * Model class file.
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
abstract class Model extends FormModel
{
	/** @var \PDO $db pdo connection */
	private $db = false;
	/** @var boolean $_isNewRecord is new record? */
	private $_isNewRecord = false;


	/**
	 * Constructor for model
	 *
	 * @access public
	 * @param boolean $new
	 * @result void
	 */
	public function __construct($new = true) {
		$this->_isNewRecord = $new;
		$this->getDbConnection();
	}
	/**
	 * Get connection to db
	 *
	 * @access public
	 * @global Registry
	 * @return void
	 */
	public function getDbConnection() {
		$this->db = Registry::get('db')->conn;
	}
	/**
	 * Is new record?
	 *
	 * @access public
	 * @return boolean
	 */
	public function isNewRecord() {
		return $this->_isNewRecord;
	}
	/**
	 * Finder data in DB
	 *
	 * @access public
	 * @param Query $query
	 * @param boolean $single
	 * @return mixed One or more data
	 */
	public static function finder($query = null, $single = false) {
		$query = ($query instanceof Query) ? $query : new Query;
		$query->table = static::tableName() . ' `m`';
		$query->objectName = get_called_class();
		$query->single = $single;
		return $query->run();
	}
	/**
	 * Before create actions
	 *
	 * @access public
	 * @return boolean
	 */
	public function beforeCreate() {
		return true;
	}
	/**
	 * Create changes
	 *
	 * @access public
	 * @return boolean
	 */
	public function create() {
		if ($this->beforeCreate()) {
			$arr = getVars($this);
			unset($arr['isNewRecord']);
			$arr_h = array_keys($arr);
			$typs = implode(',', $arr_h);
			$keys = ':'.implode(', :', $arr_h);

			$sth = $this->db->prepare(
				'INSERT INTO '. $this->tableName() . ' ('.$typs.') VALUES ('.$keys.');'
			);

			if ($sth->execute($arr)) {
				$this->_isNewRecord = false;
				$this->afterCreate();
				return true;
			}
		}
		return false;
	}
	/**
	 * After create actions
	 *
	 * @access public
	 * @global Registry
	 * @return void
	 */
	public function afterCreate() {
		// Get ID from created value
		if (array_search('id', Registry::get('db')->listFields($this->tableName()))) {
			$this->id = $this->db->lastInsertId();
		}
	}
	/**
	 * Before save actions
	 *
	 * @access public
	 * @return boolean
	 */
	public function beforeSave() {
		return true;
	}
	/**
	 * Save changes
	 * 
	 * @access public
	 * @return boolean
	 */
	public function save() {
		if ($this->isNewRecord()) {
			return $this->create();
		} else {
			if ($this->beforeSave()) {
				if ($this->update()) {
					$this->afterSave();
					return true;
				}
			}
		}
		return false;
	}
	/**
	 * After save actions
	 *
	 * @access public
	 * @return void
	 */
	public function afterSave() {
	}
	/**
	 * Before update actions
	 *
	 * @access public
	 * @return boolean
	 */
	public function beforeUpdate() {
		return true;
	}
	/**
	 * Update changes
	 *
	 * @access public
	 * @param string $where
	 * @throws MException
	 * @return boolean
	 */
	public function update($where = null) {
		if (!$this->isNewRecord()) {
			if ($this->beforeUpdate()) {
				$arr = getVars($this);
				unset($arr['isNewRecord']);

				$params = [];
				foreach ($arr AS $key => $val) {
					if ($key == 'id') {
						continue;
					}
					$params[] = $key . ' = :' . $key;
				}

				$query = 'UPDATE ' . $this->tableName() . ' SET ' . implode(', ', $params);
				if ($where) {
					$query .= ' WHERE ' . $where;
				} elseif (isset($this->id) AND !empty($this->id)) {
					$query .= ' WHERE id = :id';
				} else {
					throw new MException ('In table ' . $this->tableName() . ' option `id` not defined/not use.');
				}
				$sth = $this->db->prepare($query);

				if ($sth->execute($arr)) {
					$this->afterUpdate();
					return true;
				}
			}
		}
		return false;
	}
	/**
	 * After update actions
	 *
	 * @access public
	 * @return boolean
	 */
	public function afterUpdate() {
	}
	/**
	 * Before delete actions
	 *
	 * @access public
	 * @return boolean
	 */
	public function beforeDelete() {
		return true;
	}
	/**
	 * Delete changes
	 *
	 * @access public
	 * @return boolean
	 */
	public function delete() {
		if (!$this->isNewRecord()) {
			if ($this->beforeDelete()) {

				$sth = $this->db->prepare(
					'DELETE FROM ' . $this->tableName() . ' WHERE id=' . $this->id . ' LIMIT 1;'
				);

				if ($sth->execute()) {
					$this->afterDelete();
					unset($this);
					return true;
				}
			}
		}
		return false;
	}
	/**
	 * After delete actions
	 *
	 * @access public
	 * @return void
	 */
	public function afterDelete() {
	}
}