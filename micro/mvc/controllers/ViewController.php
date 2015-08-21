<?php /** MicroController */

namespace Micro\mvc\controllers;

use Micro\base\Exception;
use Micro\web\Response;

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
abstract class ViewController extends Controller
{
    /** @var string $layout */
    public $layout;
    /** @var bool $asWidget */
    public $asWidget = false;


    /**
     * @inheritdoc
     */
    public function action($name = 'index')
    {
        // Set widgetStack for widgets
        if (empty($GLOBALS['widgetStack'])) {
            $GLOBALS['widgetStack'] = [];
        }

        $view = null;
        $actionClass = false;


        if (!method_exists($this, 'action' . ucfirst($name))) {
            $actionClass = $this->getActionClassByName($name);
            if (!$actionClass) {
                throw new Exception('Action "' . $name . '" not found into ' . get_class($this));
            }
        }

        $filters = method_exists($this, 'filters') ? $this->filters() : [];

        $this->applyFilters($name, true, $filters, null);

        if ($actionClass) {
            /** @var \Micro\mvc\Action $cl */
            $cl = new $actionClass($this->container);
            $view = $cl->run();
        } else {
            $view = $this->{'action' . ucfirst($name)}();
        }

        if (is_object($view)) {
            $view->module = get_class($this->module);
            $view->layout = $view->layout ?: $this->layout;
            $view->view = $view->view ?: $name;
            $view->path = get_called_class();
            $view = (string)$view;
        }

        $response = $this->container->response ?: new Response;
        $response->setBody($this->applyFilters($name, false, $filters, $view));

        return $response;
    }

    /**
     * Redirect user to path
     *
     * @access public
     *
     * @param string $path path to redirect
     *
     * @return void|bool
     */
    public function redirect($path)
    {
        if (!$this->asWidget) {
            header('Location: ' . $path);
            exit();
        }

        return false;
    }
}
