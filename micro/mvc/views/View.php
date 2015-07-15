<?php /** MicroView */

namespace Micro\mvc\views;

use Micro\base\Container;
use Micro\base\Exception;
use Micro\wrappers\Html;

/**
 * Class View
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage mvc/views
 * @version 1.0
 * @since 1.0
 */
abstract class View
{
    /** @var array $styleScripts */
    public $styleScripts = [];
    /** @var bool $asWidget */
    public $asWidget = false;
    /** @var array $params */
    public $params = [];
    /** @var array $stack */
    public $stack = [];
    public $module;
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Add parameter into view
     *
     * @access public
     *
     * @param string $name parameter name
     * @param mixed $value parameter value
     *
     * @return void
     */
    public function addParameter($name, $value)
    {
        $this->params[$name] = $value;
    }

    /**
     * Widget
     *
     * @access public
     *
     * @param string $name widget name
     * @param array $options options array
     * @param bool $capture capture output
     *
     * @return string
     * @throws Exception
     */
    public function widget($name, array $options = [], $capture = false)
    {
        if (!class_exists($name)) {
            throw new Exception($this->container, 'Widget ' . $name . ' not found.');
        }

        $options = array_merge($options, ['container' => $this->container]);

        /** @var \Micro\mvc\Widget $widget widget */
        $widget = new $name($options, $this->container);
        $widget->init();

        if ($capture) {
            ob_start();
            $widget->run();
            $result = ob_get_clean();
        } else {
            $result = $widget->run();
        }

        if ($result instanceof PhpView) {
            $result->asWidget = true;
            $result->path = get_class($widget);

            $result = $result->__toString();
        }

        unset($widget);

        if ($capture) {
            return $result;
        }

        echo $result;

        return '';
    }

    /**
     * Convert object to string
     *
     * @access public
     * @return string
     */
    public function __toString()
    {
        return '' . $this->render();
    }

    /**
     * Render
     *
     * @abstract
     * @access public
     * @return mixed
     */
    abstract public function render();

    /**
     * Begin widget
     *
     * @access public
     *
     * @param string $name widget name
     * @param array $options options array
     *
     * @return mixed
     * @throws Exception
     */
    public function beginWidget($name, array $options = [])
    {
        if (!class_exists($name)) {
            throw new Exception($this->container, 'Widget ' . $name . ' not found.');
        }

        if (!empty($GLOBALS['widgetStack'][$name])) {
            throw new Exception($this->container, 'This widget (' . $name . ') already started!');
        }

        /** @var \Micro\mvc\Widget $GLOBALS ['widgetStack'][$name] widget */
        $GLOBALS['widgetStack'][$name] = new $name($options, $this->container);

        return $GLOBALS['widgetStack'][$name]->init();
    }

    /**
     * Ending widget
     *
     * @access public
     *
     * @param string $name widget name
     *
     * @throws Exception
     */
    public function endWidget($name = '')
    {
        if (!$name AND $GLOBALS['widgetStack']) {
            $widget = array_pop($GLOBALS['widgetStack']);
            $v = $widget->run();
            unset($widget);

            echo $v;

            return;
        }

        if (!class_exists($name) OR empty($GLOBALS['widgetStack'][$name])) {
            throw new Exception($this->container, 'Widget ' . $name . ' not started.');
        }

        /** @var \Micro\mvc\Widget $widget widget */
        $widget = $GLOBALS['widgetStack'][$name];
        unset($GLOBALS['widgetStack'][$name]);

        $v = $widget->run();
        unset($widget);
        echo $v;
    }

    /**
     * Register JS script
     *
     * @access public
     *
     * @param string $source file name
     * @param bool $isHead is head block
     *
     * @return void
     */
    public function registerScript($source, $isHead = true)
    {
        $this->styleScripts[] = [
            'isHead' => $isHead,
            'body' => Html::script($source)
        ];
    }

    /**
     * Register JS file
     *
     * @access public
     *
     * @param string $source file name
     * @param bool $isHead is head block
     *
     * @return void
     */
    public function registerScriptFile($source, $isHead = true)
    {
        $this->styleScripts[] = [
            'isHead' => $isHead,
            'body' => Html::scriptFile($source)
        ];
    }

    /**
     * Register CSS code
     *
     * @access public
     *
     * @param string $source file name
     * @param bool $isHead is head block
     *
     * @return void
     */
    public function registerCss($source, $isHead = true)
    {
        $this->styleScripts[] = [
            'isHead' => $isHead,
            'body' => Html::css($source)
        ];
    }

    /**
     * Register CSS file
     *
     * @access public
     *
     * @param string $source file name
     * @param bool $isHead is head block
     *
     * @return void
     */
    public function registerCssFile($source, $isHead = true)
    {
        $this->styleScripts[] = [
            'isHead' => $isHead,
            'body' => Html::cssFile($source)
        ];
    }

    /**
     * Insert styles and scripts into cache
     *
     * @access protected
     *
     * @param string $cache cache of generated page
     *
     * @return string
     */
    protected function insertStyleScripts($cache)
    {
        $heads = '';
        $ends = '';
        $result = '';

        foreach ($this->styleScripts AS $element) {
            if ($element['isHead']) {
                $heads .= $element['body'];
            } else {
                $ends .= $element['body'];
            }
        }

        $positionHead = strpos($cache, Html::closeTag('head'));
        $positionBody = strpos($cache, Html::closeTag('body'), $positionHead);

        $result .= substr($cache, 0, $positionHead);
        $result .= $heads;
        $result .= substr($cache, $positionHead, $positionBody);
        $result .= $ends;
        $result .= substr($cache, $positionHead + $positionBody);

        return $result;
    }
}