<?php

namespace Micro\db;

use Micro\base\IContainer;

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
     * @throws \Micro\base\Exception
     */
    public function __construct(array $config = [])
    {
        $this->container = $config['container'];
    }
}
