<?php /** MicroView */

namespace Micro\mvc\views;

use Micro\wrappers\Html;
use Micro\base\Exception;

/**
 * Class View
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
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
    public $asWidget=false;
    /** @var array $params */
    public $params=[];
    /** @var array $stack */
    public $stack=[];

    /**
     * Render
     *
     * @abstract
     * @access public
     * @return mixed
     */
    abstract public function render();
    /**
     * Convert object to string
     *
     * @access public
     * @return string
     */
    public function __toString() {
        return ''.$this->render();
    }
    /**
     * Add parameter into view
     *
     * @access public
     * @param string $name parameter name
     * @param mixed $value parameter value
     * @return void
     */
    public function addParameter($name, $value) {
        $this->params[$name] = $value;
    }

    /**
     * Widget
     *
     * @access public
     * @param string $name widget name
     * @param array $options options array
     * @param bool $capture capture output
     * @return string
     * @throws Exception
     */
    public function widget($name, $options=[], $capture=false)
    {
        if (!class_exists($name)) {
            throw new Exception('Widget ' . $name . ' not found.');
        }

        /** @var \Micro\mvc\Widget $widget widget */
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
     * Begin widget
     *
     * @access public
     * @param string $name widget name
     * @param array $options options array
     * @return mixed
     * @throws Exception
     */
    public function beginWidget($name, $options=[])
    {
        if (!class_exists($name)) {
            throw new Exception('Widget ' . $name . ' not found.');
        }

        if (isset($GLOBALS['widgetStack'][$name])) {
            throw new Exception('This widget (' . $name . ') already started!');
        }

        /** @var \Micro\mvc\Widget $GLOBALS ['widgetStack'][$name] widget */
        $GLOBALS['widgetStack'][$name] = new $name($options);
        return $GLOBALS['widgetStack'][$name]->init();
    }
    /**
     * @access public
     * @param string $name widget name
     * @throws Exception
     */
    public function endWidget($name)
    {
        if (!class_exists($name) OR !isset($GLOBALS['widgetStack'][$name])) {
            throw new Exception('Widget ' . $name . ' not started.');
        }

        /** @var \Micro\mvc\Widget $widget widget */
        $widget = $GLOBALS['widgetStack'][$name];
        unset($GLOBALS['widgetStack'][$name]);

        $v = $widget->run();
        unset($widget);
        echo $v;
    }

    /**
     * Insert styles and scripts into cache
     *
     * @access protected
     * @param string $cache cache of generated page
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
    /**
     * Register JS script
     *
     * @access public
     * @param string $source file name
     * @param bool $isHead is head block
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
     * @param string $source file name
     * @param bool $isHead is head block
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
     * @param string $source file name
     * @param bool $isHead is head block
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
     * @param string $source file name
     * @param bool $isHead is head block
     * @return void
     */
    public function registerCssFile($source, $isHead = true)
    {
        $this->styleScripts[] = [
            'isHead' => $isHead,
            'body' => Html::cssFile($source)
        ];
    }
}