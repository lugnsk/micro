<?php

/*
The MIT License (MIT)

Copyright (c) 2013 Oleg Lunegov

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
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
	private $db = false;
	private $_isNewRecord = false;

	/**
	 * Constructor for model
	 *
	 * @global Micro
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->isNewRecord = true;
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
			// TODO: logic
		}
		$this->afterCreate();
		return true; // TODO: patch me
	}
	/**
	 * After create actions
	 *
	 * @access public
	 * @return void
	 */
	public function afterCreate() {
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
		if ($this->isNewRecord) {
			return $this->create();
		} else {
			if ($this->beforeSave()) {
				// TODO: logic
			}
			$this->afterSave();
			return true; // TODO: patch me
		}
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
	 * @return boolean
	 */
	public function update() {
		if (!$this->isNewRecord) {
			if ($this->afterSave()) {
				// TODO: logic
			}
			$this->afterUpdate();
			return true; // TODO: patch me
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
		if (!$this->isNewRecord) {
			if ($this->beforeDelete()) {
				// TODO: logic
			}
			$this->afterDelete();
			return true; // TODO: patch me
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