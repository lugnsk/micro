<?php

namespace Micro\Db;

use Micro\Base\IContainer;

/**
 * Threads class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Db
 * @version 1.0
 * @since 1.0
 */
abstract class Connection implements IConnection
{
    /** @var IContainer $container Container container */
    protected $container;


    /**
     * Construct for this class
     *
     * @access public
     *
     * @param array $config configuration array
     *
     * @result void
     * @throws \Micro\Base\Exception
     */
    public function __construct(array $config = [])
    {
        $this->container = $config['container'];
    }
}
