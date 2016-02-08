<?php /** MicroResolver */

namespace Micro\Resolver;

use Micro\Base\IContainer;

/**
 * Resolver class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Resolver
 * @version 1.0
 * @since 1.0
 * @abstract
 */
abstract class Resolver implements IResolver
{
    /** @var IContainer $container Container is a container for components and options */
    protected $container;


    /**
     * Constructor Resolver
     *
     * @access public
     *
     * @param IContainer $container Container is a container for components and options
     *
     * @result void
     */
    public function __construct(IContainer $container)
    {
        $this->container = $container;
    }
}
