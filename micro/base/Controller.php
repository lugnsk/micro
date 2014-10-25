<?php /** MicroController */

namespace Micro\base;

use \Micro\Micro;
use Micro\wrappers\Html;
use \Micro\web\Language;

/**
 * Controller class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
abstract class Controller
{
    /** @var mixed $module module name */
    public static $module;
    /** @var string $layout layout name */
    public $layout;
    /** @var string $defaultAction default run action */
    public $defaultAction = 'index';
    /** @var array $styleScripts styles and scripts for head and body */
    private $styleScripts = [];
    /** @var boolean $asWidget is a widget? */
    public $asWidget = false;
    /** @var array $widgetStack widgets stack */
    private $widgetStack = [];


    /**
     * Constructor for this class
     *
     * @access public
     * @global Micro
     * @global Registry
     * @result void
     */
    public function __construct()
    {
        if ($module = Registry::get('request')->getModules()) {
            $path = Micro::getInstance()->config['AppDir'] . $module . '/' . ucfirst(basename($module)) . 'Module.php';

            if (file_exists($path)) {
                $path = substr(basename($path), 0, -4);
                self::$module = new $path();
            }
        }
    }

    /**
     * Run action
     *
     * @access public
     * @param string $name action name to run
     * @return void
     * @throws Exception method not declared
     */
    public function action($name = 'index')
    {
        $config = Micro::getInstance()->config;
        if (isset($config['errorController']) AND (get_class($this) == $config['errorController'])) {
            if (isset($config['errorAction']) AND $config['errorAction']) {
                $name = $config['errorAction'];
            }
        }

        $action = 'action' . ucfirst($name);
        if (!method_exists($this, $action)) {
            $action = 'action' . ucfirst($this->defaultAction);

            if (!method_exists($this, $action)) {
                if (isset($config['errorController']) AND $config['errorController']) {
                    if (isset($config['errorAction']) AND $config['errorAction']) {
                        /** @var Controller $cls recreate controller */
                        $cls = new $config['errorController'];
                        $cls->action($config['errorAction']);
                        return;

                    }
                }
                throw new Exception('Method ' . $name . ' is not declared in ' . get_class($this) . '.');
            }
        }

        $this->$action();
    }

    /**
     * Render partial a view
     *
     * @access protected
     * @param string $view view name
     * @param array $data arguments array
     * @return string
     */
    protected function renderPartial($view, $data = [])
    {
        $lay = $this->layout;
        $wid = $this->asWidget;

        $this->layout = null;
        $this->asWidget = false;
        $output = $this->render($view, $data);
        $this->layout = $lay;
        $this->asWidget = $wid;

        return $output;
    }

    /**
     * Render insert data into view
     *
     * @access protected
     * @param string $view view name
     * @param array $data arguments array
     * @return string
     */
    protected function render($view, $data = [])
    {
        if (empty($view)) {
            return false;
        }
        return $this->renderRawData($this->renderFile($this->getViewFile($view), $data));
    }

    /**
     * Render raw data in layout
     *
     * @access protected
     * @global Micro
     * @global Registry
     * @param string $data arguments array
     * @return string
     */
    protected function renderRawData($data = '')
    {
        $layoutPath = null;
        if (!$this->asWidget AND $this->layout) {
            $layoutPath = $this->getLayoutFile(
                Micro::getInstance()->config['AppDir'],
                Registry::get('request')->getModules()
            );
        }

        if ($layoutPath) {
            $data = $this->insertStyleScripts($this->renderFile($layoutPath, ['content' => $data]));
        }
        return $data;
    }

    /**
     * Get view file
     *
     * @access private
     * @param string $view view file name
     * @return string
     */
    private function getViewFile($view)
    {
        $calledClass = get_called_class();

        // Calculate path to view
        if (substr($calledClass, 0, strpos($calledClass, '\\')) == 'App') {
            $path = Micro::getInstance()->config['AppDir'];
        } else {
            $path = Micro::getInstance()->config['MicroDir'];
        }

        $cl = strtolower(dirname(strtr($calledClass, '\\', '/')));

        $cl = substr($cl, strpos($cl, '/'));
        if ($this->asWidget) {
            $path .= $cl . '/views/' . $view . '.php';
        } else {
            $className = str_replace('controller', '',
                strtolower(basename(str_replace('\\', '/', '/' . get_called_class()))));
            $path .= dirname($cl) . '/views/' . $className . '/' . $view . '.php';
        }
        return $path;
    }

    /**
     * Render file by path
     *
     * @access protected
     * @param string $fileName file name
     * @param array $data arguments array
     * @return string
     * @throws Exception widget not declared
     */
    protected function renderFile($fileName, $data = [])
    {
        $lang = new Language($fileName);

        extract($data, EXTR_PREFIX_SAME, 'data');
        ob_start();
        include str_replace('\\', '/', $fileName);

        if (!empty($this->widgetStack)) {
            throw new Exception(count($this->widgetStack) . ' widgets not endings.');
        }

        return ob_get_clean();
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
     * Get layout path
     *
     * @access protected
     * @param string $baseDir path to base dir
     * @param string $module module name
     * @return string
     * @throws Exception
     */
    protected function getLayoutFile($baseDir, $module)
    {
        $layout = $baseDir . '/' . (($module) ? $module . '/' : $module);
        $afterPath = 'views/layouts/' . ucfirst($this->layout) . '.php';

        if (!file_exists($layout . $afterPath)) {
            if (file_exists($baseDir . '/' . $afterPath)) {
                return $baseDir . '/' . $afterPath;
            }
            throw new Exception('Layout ' . ucfirst($this->layout) . ' not found.');
        }
        return $layout . $afterPath;
    }

    /**
     * Redirect user to path
     *
     * @access public
     * @param string $path path to redirect
     * @return void
     */
    public function redirect($path)
    {
        header('Location: ' . $path);
        exit();
    }

    // Styles and Scripts

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

    // Widgets:

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
            $widget->run();
            $result = null;
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

        $widget->run();
        unset($widget);
    }
}