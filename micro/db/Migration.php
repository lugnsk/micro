<?php /** MicroMigration */

namespace Micro\db;

use Micro\base\Container;

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
    /** @var Container $container */
    protected $container;


    /**
     * Constructor for model
     *
     * @access public
     * @param Container $container
     * @result void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
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