<?php /** MicroFilesystem */

namespace Micro\wrappers;

use Micro\base\Exception;
use Micro\files;

/**
 * Class Filesystem is a abstraction access for filesystems
 *
 * This wrapper is a thin layer to work with files in the difference file repositories,
 * by the removal common methods in this wrapper, and the implementation of specific storage
 * in separate classes driver.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage wrappers
 * @version 1.0
 * @since 1.0
 */
class Filesystem
{
    /** @var \Micro\files\File $driver Current file driver */
    protected $driver;

    /**
     * Constructor make a connection to driver
     *
     * @access public
     *
     * @param array $params Parameters for driver and 'driver' name
     *
     * @result void
     */
    public function __construct( array $params = [] )
    {
        $this->setDriver($params);
    }

    /**
     * Set driver usage driver params
     *
     * @access public
     *
     * @param array $params Driver params
     *
     * @return void
     */
    public function setDriver( array $params = [] )
    {
        $driverName = !empty($params['driver']) ? $params['driver'] : 'local';

        $this->driver = new $driverName($params);
    }

    /**
     * Call driver function with Filesystem object
     *
     * @access public
     *
     * @param string $methodName Method name to call
     * @param array  $arguments Arguments for method
     *
     * @return mixed
     * @throws \Micro\base\Exception
     */
    public function __call( $methodName, array $arguments=[] )
    {
        if (!method_exists($this->driver, $methodName)) {
            throw new Exception('Method `' . $methodName.'` not defined in `' . get_class($this->driver) . '` driver.');
        }
        return call_user_func_array( [$this->driver, $methodName], $arguments );
    }
}

?>

