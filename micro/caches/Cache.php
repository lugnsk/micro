<?php /** MicroCache */

namespace Micro\caches;

/**
 * Interface Cache
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage caches
 * @version 1.0
 * @since 1.0
 */
interface Cache
{
    /**
     * Check driver
     *
     * @access public
     * @return mixed
     */
    public function check();

    /**
     * Get value by name
     *
     * @access public
     * @param string $name key name
     * @return mixed
     */
    public function get($name);

    /**
     * Set value of element
     *
     * @access public
     * @param string $name key name
     * @param mixed $value value
     * @return mixed
     */
    public function set($name, $value);

    /**
     * Delete by key name
     *
     * @access public
     * @param string $name key name
     * @return mixed
     */
    public function delete($name);

    /**
     * Clean all data from cache
     *
     * @access public
     * @return mixed
     */
    public function clean();

    /**
     * Summary info about cache
     *
     * @access public
     * @return mixed
     */
    public function info();

    /**
     * Get meta-data of key id
     *
     * @access public
     * @param string $id key id
     * @return mixed
     */
    public function getMeta($id);

    /**
     * Increment value
     *
     * @access public
     * @param string $name key name
     * @param int $offset increment value
     * @return mixed
     */
    public function increment($name, $offset = 1);

    /**
     * Decrement value
     *
     * @access public
     * @param string $name key name
     * @param int $offset decrement value
     * @return mixed
     */
    public function decrement($name, $offset = 1);
}