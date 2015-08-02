<?php /** MicroWidget */

namespace Micro\mvc;

use Micro\base\IContainer;

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
    /** @var IContainer $container */
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
    public function __construct(array $args = [])
    {
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
