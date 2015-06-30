<?php /** MicroWidget */

namespace Micro\mvc;

use Micro\base\Registry;
use Micro\web\Request;

/**
 * Class Controller
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
abstract class Widget
{
    protected $container;


    /**
     * Constructor for widgets
     *
     * @access public
     *
     * @param array $args arguments array
     *
     * @result void
     */
    public function __construct( array $args = [] )
    {
        $this->container = array_key_exists('container', $args) ? $args['container'] : null;

        foreach ($args AS $name => $value) {
            $this->$name = $value;
        }
    }

    /**
     * Initialize widget
     * @abstract
     */
    abstract public function init();

    /**
     * Run widget
     * @abstract
     */
    abstract public function run();
}