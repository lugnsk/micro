<?php /** MicroMigration */

namespace Micro\mvc\models;

use Micro\base\IContainer;

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
    /** @var IContainer $container */
    protected $container;


    /**
     * Constructor for model
     *
     * @access public
     * @param IContainer $container
     * @result void
     */
    public function __construct(IContainer $container)
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
