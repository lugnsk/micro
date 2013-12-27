<?php /** MicroMigration */

namespace Micro\db;

use Micro\base\Registry;

/**
 * Migration class file.
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
abstract class Migration
{
    /** @var DbConnection $db connection to db */
    protected $db = false;


    /**
     * Constructor for model
     *
     * @access public
     * @result void
     */
    public function __construct()
    {
        $this->getDbConnection();
    }

    /**
     * Get connection to db
     *
     * @access public
     * @global Registry
     * @return void
     */
    public function getDbConnection()
    {
        $this->db = Registry::get('db');
    }

    /**
     * Upgrade DB
     *
     * @access public
     * @return void
     * @abstract
     */
    abstract public function up();

    /**
     * Downgrade DB
     *
     * @access public
     * @return void
     * @abstract
     */
    abstract public function down();
}