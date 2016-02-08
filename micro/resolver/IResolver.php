<?php

namespace Micro\Resolver;

/**
 * Interface IResolver
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Resolver
 * @version 1.0
 * @since 1.0
 */
interface IResolver
{
    /**
     * Get instance application
     *
     * @access public
     *
     * @return mixed
     * @abstract
     */
    public function getApplication();
}
