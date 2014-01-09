<?php

/**
 * MicroModel class file.
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
class MicroModel
{
	/** @var MicroDbConnection $db */
	private $db = false;
	/** @var boolean $_isNewRecord */
	private $_isNewRecord = false;

	/**
	 * Constructor for model
	 *
	 * @global Micro
	 * @access public
	 * @return void
	 */
	public function __construct($new = true) {
		$this->_isNewRecord = $new;
		$this->db = Micro::getInstance()->db->conn;
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
	 * @param MicroQuery $query
	 * @param bolean $single
	 * @return mixed One or more data
	 */
	public static function finder($query = null, $single = false) {
		$query = ($query instanceof MicroQuery) ? $query : new MicroQuery;
		$query->table = static::tableName();
		$query->objectName = get_called_class();
		$query->single = $single;
		return $query->run($single);
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
	 * @return void
	 */
	public function afterCreate() {
		// Get ID from created value
		if (array_search('id', Micro::getInstance()->db->listFields($this->tableName()))) {
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
	 * @return boolean
	 */
	public function update($where = null) {
		if (!$this->isNewRecord()) {
			if ($this->beforeUpdate()) {
				$arr = getVars($this);
				unset($arr['isNewRecord']);

				$params = array();
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
					throw new MicroException ('В таблице ' . $this->tableName() . ' опция id не существует/не ипользуется.');
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
	 * @param string $where
	 * @return boolean
	 */
	public function delete($where = null) {
		if (!$this->isNewRecord()) {
			if ($this->beforeDelete()) {
				$arr = getVars($this);
				unset($arr['isNewRecord']);

				$keys = array_keys($arr);
				$params = array();

				foreach ($keys AS $key) {
					$params[] = $key . ' = :' . $key;
				}

				$sth = $this->db->prepare(
					'DELETE FROM ' . $this->tableName() . ' WHERE ' . implode(' AND ', $params) . ' LIMIT 1;'
				);

				if ($sth->execute($arr)) {
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