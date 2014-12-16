<?php /** MicroController */

namespace Micro\mvc;

use Micro\Micro;
use Micro\base\Registry;
use Micro\base\Exception;

/**
 * Class Controller
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage mvc
 * @version 1.0
 * @since 1.0
 */
abstract class Controller
{
    /** @var bool $asWidget */
    public $asWidget = false;
    /** @var string $module */
    public $module;
    /** @var string $layout */
    public $layout;

    /**
     * Constructor controller
     *
     * @access public
     * @result void
     */
    public function __construct()
    {
        // if module defined
        if ($module = Registry::get('request')->getModules()) {
            $app = Micro::getInstance()->config['AppDir'];

            $path = $app . str_replace('\\','/', $module) . '/' .
                ucfirst(basename(str_replace('\\','/', $module))) . 'Module.php';

            // search module class
            if (file_exists($path)) {
                $path = substr(str_replace('/', '\\', str_replace($app, 'App', $path)), 0, -4);
                $this->module = new $path();
            }
        }
    }

    /**
     * Run action
     *
     * @access public
     * @param string $name action name
     * @return void
     * @throws Exception
     */
    public function action($name = 'index')
    {
        $view = null;
        $actionClass = FALSE;

        if (!method_exists($this, 'action' . ucfirst($name))) {
            if (!$actionClass = $this->getActionClassByName($name)) {
                throw new Exception('Action "'.$name.'" not found into '.get_class($this));
            }
        }
        $filters = method_exists($this, 'filters') ? $this->filters() : null;

        // @TODO: pre filters
        if ($actionClass) {
            $cl = new $actionClass;
            $view = $cl->run();
        } else {
            $view = $this->{'action' . ucfirst($name)}();
        }
        // @TODO: post filters

        if (is_object($view)) {
            $view->layout = (!$view->layout) ? $this->layout : $view->layout;
            $view->view = (!$view->view) ? $name : $view->name;
            $view->path = get_called_class();
        }
        echo $view;
    }

    /**
     * Get action class by name
     *
     * @access public
     * @param string $name action name
     * @return bool
     */
    public function getActionClassByName($name) {
        if (method_exists($this, 'actions')) {
            $actions = $this->actions();
            if (isset($actions[$name]) AND class_exists($actions[$name])) {
                return $actions[$name];
            }
        }
        return FALSE;
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
}