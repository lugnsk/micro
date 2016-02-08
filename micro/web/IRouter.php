<?php /** MicroInterfaceRouter */

namespace Micro\Web;

/**
 * Interface IRouter
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Web
 * @version 1.0
 * @since 1.0
 * @interface
 */
interface IRouter
{
    /**
     * Parsing uri
     *
     * @access public
     *
     * @param string $uri current check URI
     * @param string $method current Request method
     *
     * @return string
     */
    public function parse($uri, $method = 'GET');
}
