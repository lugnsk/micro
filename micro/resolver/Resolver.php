<?php /** MicroResolver */

namespace Micro\resolver;

use Micro\base\IContainer;

/**
 * Resolver class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage resolver
 * @version 1.0
 * @since 1.0
 * @abstract
 */
abstract class Resolver
{
    /** @var IContainer $container Container is a container for components and options */
    protected $container;


    /**
     * Constructor Resolver
     *
     * @access public
     *
     * @param Container $container Container is a container for components and options
     *
     * @result void
     */
    public function __construct(IContainer $container)
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
