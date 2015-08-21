<?php /** MicroView */

namespace Micro\mvc\views;

use Micro\base\Exception;
use Micro\base\IContainer;
use Micro\mvc\Module;
use Micro\web\Html;

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
abstract class View implements IView
{
    /** @var array $styleScripts */
    public $styleScripts = [];
    /** @var bool $asWidget */
    public $asWidget = false;
    /** @var array $params */
    public $params = [];
    /** @var array $stack */
    public $stack = [];
    /** @var Module $module */
    public $module;
    /** @var IContainer $container */
    public $container;

    /**
     * @param IContainer $container
     */
    public function __construct(IContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function addParameter($name, $value)
    {
        $this->params[$name] = $value;
    }

    /**
     * @inheritDoc
     */
    public function widget($name, array $options = [], $capture = false)
    {
        if (!class_exists($name)) {
            throw new Exception('Widget ' . $name . ' not found.');
        }

        $options = array_merge($options, ['container' => $this->container]);

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

        if ($result instanceof PhpView) {
            $result->asWidget = true;
            $result->path = get_class($widget);

            $result = (string)$result;
        }

        unset($widget);

        if ($capture) {
            return $result;
        }

        echo $result;

        return '';
    }

    /**
     * @inheritDoc
     */
    public function beginWidget($name, array $options = [])
    {
        if (!class_exists($name)) {
            throw new Exception('Widget ' . $name . ' not found.');
        }

        if (!empty($GLOBALS['widgetStack'][$name])) {
            throw new Exception('This widget (' . $name . ') already started!');
        }

        $options = array_merge($options, ['container' => $this->container]);

        $GLOBALS['widgetStack'][$name] = new $name($options, $this->container);

        /** @noinspection PhpUndefinedMethodInspection */

        return $GLOBALS['widgetStack'][$name]->init();
    }

    /**
     * @inheritDoc
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
     * @return string
     */
    public function __toString()
    {
        return '' . $this->render();
    }

    /**
     * @inheritDoc
     */
    public function registerScript($source, $isHead = true)
    {
        $this->styleScripts[] = [
            'isHead' => $isHead,
            'body' => Html::script($source)
        ];
    }

    /**
     * @inheritDoc
     */
    public function registerScriptFile($source, $isHead = true)
    {
        $this->styleScripts[] = [
            'isHead' => $isHead,
            'body' => Html::scriptFile($source)
        ];
    }

    /**
     * @inheritDoc
     */
    public function registerCss($source, $isHead = true)
    {
        $this->styleScripts[] = [
            'isHead' => $isHead,
            'body' => Html::css($source)
        ];
    }

    /**
     * @inheritDoc
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
