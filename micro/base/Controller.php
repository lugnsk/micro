<?php /** MicroController */

namespace Micro\base;

use Micro\Micro;

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
     * @param string $name
     * @return void
     * @throws Exception method not declared
     */
    public function action($name = 'index')
    {
        $action = 'action' . ucfirst($name);

        if (!method_exists($this, $action)) {
            $action = 'action' . ucfirst($this->defaultAction);

            if (!method_exists($this, $action)) {
                throw new Exception('Method ' . $name . ' is not declared.');
            }
        }

        $this->$action();
    }

    /**
     * Render partial a view
     *
     * @access protected
     * @param string $view view name
     * @param array $data
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
     * Render view
     *
     * @access protected
     * @global Micro
     * @global Registry
     * @param string $view
     * @param array $data
     * @return string
     */
    protected function render($view, $data = [])
    {
        if (empty($view)) {
            return false;
        }
        $path = $this->getViewFile($view);

        $layoutPath = null;
        if (!$this->asWidget AND $this->layout) {
            $layoutPath = $this->getLayoutFile(
                Micro::getInstance()->config['AppDir'],
                Registry::get('request')->getModules()
            );
        }

        // Render view
        $output = $this->renderFile($path, $data);
        if ($layoutPath) {
            $output = $this->renderFile($layoutPath, ['content' => $output]);
        }

        return $output;
    }

    /**
     * Get view file
     *
     * @access private
     * @param $view
     * @return string
     */
    private function getViewFile($view)
    {
        $calledClass = get_called_class();

        // Calculate path to view
        if ( substr($calledClass, 0, strpos($calledClass, '\\')) == 'App' ) {
            $path = Micro::getInstance()->config['AppDir'];
        } else {
            $path = Micro::getInstance()->config['MicroDir'];
        }

        $cl = strtolower(dirname(strtr($calledClass, '\\', '/')));

        $cl = substr($cl, strpos($cl, '/'));
        if ($this->asWidget) {
            $path .= $cl . '/views/' . $view . '.php';
        } else {
            $className = str_replace('controller', '', strtolower(Registry::get('request')->getController()));
            $path .= dirname($cl) . '/views/' . $className . '/' . $view . '.php';
        }
        return $path;
    }

    /**
     * Render file by path
     *
     * @access protected
     * @param string $fileName
     * @param array $data
     * @return string
     * @throws Exception widget not declared
     */
    protected function renderFile($fileName, $data = [])
    {
        $fileNameLang = substr($fileName, 0, -3);
        $lang = (file_exists($fileNameLang)) ? new Language($fileNameLang) : null;
        unset($fileNameLang);

        extract($data, EXTR_PREFIX_SAME, 'data');
        ob_start();
        include str_replace('\\', '/', $fileName);

        if (!empty($this->widgetStack)) {
            throw new Exception(count($this->widgetStack) . ' widgets not endings.');
        }
        return ob_get_clean();
    }

    /**
     * Get layout path
     *
     * @access protected
     * @param string $baseDir
     * @param string $module
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
            throw new Exception('Layout '.ucfirst($this->layout).' not found.');
        }
        return $layout . $afterPath;
    }

    /**
     * Redirect user to path
     *
     * @access public
     * @param string $path
     * @return void
     */
    public function redirect($path)
    {
        header('Location: ' . $path);
        exit();
    }

    // Widgets:

    /**
     * Render a widget
     *
     * @access public
     * @param string $name
     * @param array $options
     * @param bool $capture
     * @return mixed
     * @throws Exception
     */
    public function widget($name, $options = [], $capture = false)
    {
        if (!class_exists($name)) {
            throw new Exception('Widget ' . $name . ' not found.');
        }

        /** @var \Micro\base\Widget $widget */
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
     * @param $name
     * @param array $options
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

        /** @var \Micro\base\Widget $GLOBALS['widgetStack'][$name] */
        $GLOBALS['widgetStack'][$name] = new $name($options);
        return $GLOBALS['widgetStack'][$name]->init();
    }

    /**
     * End of widget
     *
     * @access public
     * @param string $name
     * @return void
     * @throws Exception
     */
    public function endWidget($name)
    {
        if (!class_exists($name) OR !isset($GLOBALS['widgetStack'][$name])) {
            throw new Exception('Widget ' . $name . ' not started.');
        }

        /** @var \Micro\base\Widget $widget */
        $widget = $GLOBALS['widgetStack'][$name];
        unset($GLOBALS['widgetStack'][$name]);

        $widget->run();
        unset($widget);
    }
}