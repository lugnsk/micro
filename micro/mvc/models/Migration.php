<?php /** MicroMigration */

namespace Micro\Mvc\Models;

use Micro\Base\IContainer;

/**
 * Migration class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Mvc\Models
 * @version 1.0
 * @since 1.0
 * @abstract
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
