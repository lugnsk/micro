<?php /** MicroAction */

namespace Micro\mvc;

/**
 * Class Action
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage mvc
 * @version 1.0
 * @since 1.0
 */
abstract class Action
{
    /**
     * Running action
     *
     * @access public
     *
     * @return mixed
     */
    abstract public function run();
}