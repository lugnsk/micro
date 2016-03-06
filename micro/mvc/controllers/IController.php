<?php /** MicroInterfaceController */

namespace Micro\Mvc\Controllers;

use Micro\Web\IResponse;

/**
 * Class IController
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Mvc\Controllers
 * @version 1.0
 * @since 1.0
 * @interface
 */
interface IController
{
    /**
     * Apply filters
     *
     * @access public
     *
     * @param string $action current action name
     * @param bool $isPre is pre or post
     * @param array $filters defined filters
     * @param string $data data to parse
     *
     * @return null|string
     * @throws \Micro\Base\Exception error on filter
     */
    public function applyFilters($action, $isPre = true, array $filters = [], $data = null);

    /**
     * Master action
     *
     * @access public
     *
     * @param string $name Called action name
     *
     * @return string|IResponse
     * @throws \Micro\Base\Exception
     */
    public function action($name = 'index');
}
