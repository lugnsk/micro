<?php /** MicroController */

namespace Micro\Mvc\Controllers;

use Micro\Base\Exception;
use Micro\Base\IContainer;
use Micro\Mvc\Module;
use Micro\Web\IResponse;
use Micro\Web\Response;

/**
 * Class Controller
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Mvc\Controllers
 * @version 1.0
 * @since 1.0
 * @abstract
 */
abstract class Controller implements IController
{
    /** @var Module $module */
    public $module;
    /** @var IResponse $response Response HTTP data */
    public $response;
    /** @var IContainer $container */
    protected $container;

    /**
     * Constructor controller
     *
     * @access public
     *
     * @param IContainer $container
     * @param string $modules
     *
     * @result void
     */
    public function __construct(IContainer $container, $modules = '')
    {
        $this->container = $container;

        // if module defined
        if ($modules) {
            $modules = str_replace('\\', '/', $modules);
            $app = $this->container->kernel->getAppDir();
            $path = $app . $modules . '/' . ucfirst(basename($modules)) . 'Module.php';
            $className = substr(str_replace('/', '\\', str_replace($app, 'App', $path)), 0, -4);

            // search module class
            if (file_exists($path) && class_exists($className) && is_subclass_of($className, '\Micro\Mvc\Module')) {
                $this->module = new $className($this->container);
            }
        }

        if (!$this->response = $this->container->response) {
            $this->response = new Response;
        }
    }

    /**
     * Apply filters
     *
     * @access public
     *
     * @param string $action current action name
     * @param bool $isPre is pre or post
     * @param array $filters defined filters
     * @param string $data data to parse
     *
     * @return null|string
     * @throws Exception
     */
    public function applyFilters($action, $isPre = true, array $filters = [], $data = null)
    {
        if (!$filters) {
            return $data;
        }

        foreach ($filters as $filter) {
            if (empty($filter['class']) || !class_exists($filter['class'])) {
                continue;
            }

            if (empty($filter['actions']) || !in_array($action, $filter['actions'], true)) {
                continue;
            }

            /** @var \Micro\Filter\IFilter $_filter */
            $_filter = new $filter['class']($action, $this->container);

            $res = $isPre ? $_filter->pre($filter) : $_filter->post($filter + ['data' => $data]);
            if (!$res) {
                if (!empty($_filter->result['redirect'])) {
                    header('Location: ' . $_filter->result['redirect']);

                    die();
                }
                throw new Exception($_filter->result['message']);
            }
            $data = $res;
        }

        return $data;
    }

    /**
     * Get action class by name
     *
     * @access public
     *
     * @param string $name action name
     *
     * @return bool
     */
    public function getActionClassByName($name)
    {
        if (method_exists($this, 'actions')) {
            $actions = $this->actions();

            if (
                !empty($actions[$name]) &&
                class_exists($actions[$name]) &&
                is_subclass_of($actions[$name], '\Micro\Mvc\Action')
            ) {
                return $actions[$name];
            }
        }

        return false;
    }
}
