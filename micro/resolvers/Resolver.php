<?php /** MicroResolver */

namespace Micro\resolvers;

use Micro\base\Registry;

/**
 * Resolver class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage resolvers
 * @version 1.0
 * @since 1.0
 * @abstract
 */
abstract class Resolver
{
    /** @var Registry $container Registry is a container for components and options */
    protected $container;


    /**
     * Constructor Resolver
     *
     * @access public
     *
     * @param Registry $container Registry is a container for components and options
     *
     * @result void
     */
    public function __construct(Registry $container)
    {
        $this->container = $container;
    }

    /**
     * Get instance application
     *
     * @access public
     *
     * @return mixed
     * @abstract
     */
    abstract public function getApplication();
}
