<?php /** MicroInterfaceDispatcher */

namespace Micro\base;

/**
 * Interface IDispatcher
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
interface IDispatcher
{
    /**
     * Add listener on event
     *
     * @access public
     *
     * @param string $listener listener name
     * @param array $event ['Object', 'method']
     * @param int|null $prior priority
     *
     * @return void
     */
    public function addListener($listener, array $event = [], $prior = null);

    /**
     * Send signal to run event
     *
     * @access public
     *
     * @param string $listener listener name
     * @param array $params Signal parameters
     *
     * @return mixed
     */
    public function signal($listener, array $params = []);
}