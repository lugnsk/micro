<?php /** MicroMigration */

namespace Micro\db;

use Micro\base\MRegistry;

/**
 * MMigration class file.
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
class MMigration {
	/** @var MDbConnection $db connection to db */
	private $db = false;


	/**
	 * Constructor for model
	 *
	 * @access public
	 * @result void
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