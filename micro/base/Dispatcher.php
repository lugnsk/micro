<?php /** MicroDispatcher */

namespace Micro\base;

/**
 * Dispatcher class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class Dispatcher
{
    /** @var array $listeners listeners objects on events */
    protected $listeners = [];


    /**
     * Add listener on event
     *
     * @access public
     * @param string $listener listener name
     * @param string $event event name
     * @return void
     */
    public function addListener($listener, $event) {
        if (!isset($this->listeners[$event])) {
            $this->listeners[$event] = [];
        }
        if (!array_search($listener, $this->listeners[$event])) {
            $this->listeners[$event][] = $listener;
        }
    }

    /**
     * Send signal to run event
     *
     * @access public
     * @param string $event event name
     * @param array|null $args event arguments
     * @return void
     */
    public function signal($event, $args=null) {
        foreach ( $this->listeners AS $key => $objs ) {
            if ($key == $event) {
                foreach ($objs AS $obj) {
                    if ($args) {
                        $obj->{$event}($args);
                    } else {
                        $obj->{$event}();
                    }
                }
            }
        }
    }
}