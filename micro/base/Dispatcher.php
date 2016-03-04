<?php /** MicroDispatcher */

namespace Micro\Base;

/**
 * Dispatcher class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Base
 * @version 1.0
 * @since 1.0
 */
class Dispatcher implements IDispatcher
{
    /** @var array $listeners listeners objects on events */
    protected $listeners = [];

    /**
     * Add listener on event
     *
     * @access public
     *
     * @param string $listener listener name
     * @param array $event ['Object', 'method'] or callable
     * @param int|null $prior priority
     *
     * @return bool
     */
    public function addListener($listener, $event, $prior = null)
    {
        if (!is_callable($event)) {
            return false;
        }

        if (!$prior) {
            $this->listeners[$listener][] = $event;
        } else {
            array_splice($this->listeners, $prior, 0, $event);
        }

        return true;
    }

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
    public function signal($listener, array $params = [])
    {
        if ($this->listeners && array_key_exists($listener, $this->listeners)) {
            foreach ($this->listeners[$listener] as $listen) {
                return call_user_func($listen, $params);
            }
        }

        return false;
    }
}
