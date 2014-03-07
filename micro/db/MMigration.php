<?php

class MMigration {

	private $db = false;

	/**
	 * Constructor for model
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->getDbConnection();
	}
	/**
	 * Get connection to db
	 *
	 * @access public
	 * @global MRegistry
	 * @return void
	 */
	public function getDbConnection() {
		$this->db = MRegistry::get('db')->conn;
	}
	/**
	 * Upgrade DB
	 *
	 * @access public
	 * @return void
	 */
	public function up() {
	}
	/**
	 * Downgrade DB
	 *
	 * @access public
	 * @return void
	 */
	public function down() {
	}
}