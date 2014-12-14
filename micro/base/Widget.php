<?php /** MicroWidget */

namespace Micro\base;

use Micro\mvc\Controller;

/**
 * Widget class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
abstract class Widget extends Controller
{
    /**
     * Constructor for widgets
     *
     * @access public
     * @param array $args arguments array
     * @result void
     */
    public function __construct($args = [])
    {
        foreach ($args AS $name => $value) {
            $this->$name = $value;
        }
        $this->asWidget = true;
    }

    /**
     * Render a widget
     *
     * @access public
     * @param string $name widget name
     * @param array $options widget options
     * @param bool $capture capture output?
     * @return mixed
     * @throws Exception
     */
    public function widget($name, $options = [], $capture = false)
    {
        if (!class_exists($name)) {
            throw new Exception('Widget ' . $name . ' not found.');
        }

        /** @var \Micro\base\Widget $widget widget */
        $widget = new $name($options);
        $widget->init();

        if ($capture) {
            ob_start();
            $widget->run();
            $result = ob_get_clean();
        } else {
            $result = $widget->run();
        }
        unset($widget);
        return $result;
    }

    /**
     * Start render widget
     *
     * @access public
     * @param string $name widget name
     * @param array $options widget options
     * @return mixed
     * @throws Exception
     */
    public function beginWidget($name, $options = [])
    {
        if (!class_exists($name)) {
            throw new Exception('Widget ' . $name . ' not found.');
        }

        if (isset($GLOBALS['widgetStack'][$name])) {
            throw new Exception('This widget (' . $name . ') already started!');
        }

        /** @var \Micro\base\Widget $GLOBALS ['widgetStack'][$name] widget */
        $GLOBALS['widgetStack'][$name] = new $name($options);
        return $GLOBALS['widgetStack'][$name]->init();
    }

    /**
     * End of widget
     *
     * @access public
     * @param string $name widget name
     * @return void
     * @throws Exception
     */
    public function endWidget($name)
    {
        if (!class_exists($name) OR !isset($GLOBALS['widgetStack'][$name])) {
            throw new Exception('Widget ' . $name . ' not started.');
        }

        /** @var \Micro\base\Widget $widget widget */
        $widget = $GLOBALS['widgetStack'][$name];
        unset($GLOBALS['widgetStack'][$name]);

        $v = $widget->run();
        unset($widget);
        echo $v;
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