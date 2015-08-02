<?php

namespace Micro\mvc\controllers;

use Micro\base\Exception;
use Micro\base\IContainer;
use Micro\mvc\Module;
use Micro\web\IResponse;
use Micro\web\Response;


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
            $app = $this->container->kernel->getAppDir();

            $path = $app . str_replace('\\', '/', $modules) . '/' .
                ucfirst(basename(str_replace('\\', '/', $modules))) . 'Module.php';

            // search module class
            if (file_exists($path)) {
                $path = substr(str_replace('/', '\\', str_replace($app, 'App', $path)), 0, -4);
                $this->module = new $path($this->container);
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
     * @throws Exception error on filter
     */
    public function applyFilters($action, $isPre = true, array $filters = [], $data = null)
    {
        if (!$filters) {
            return $data;
        }

        foreach ($filters AS $filter) {
            if (empty($filter['class']) || !class_exists($filter['class'])) {
                continue;
            }
            if (empty($filter['actions']) || !in_array($action, $filter['actions'], true)) {
                continue;
            }

            /** @var \Micro\filter\IFilter $_filter */
            $_filter = new $filter['class']($action, $this->container);

            $res = $isPre ? $_filter->pre($filter) : $_filter->post($filter + ['data' => $data]);
            if (!$res) {
                if (!empty($_filter->result['redirect'])) {
                    header('Location: ' . $_filter->result['redirect']);
                    die();
                }
                throw new Exception($this->container, $_filter->result['message']);
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
            if (!empty($actions[$name]) && class_exists($actions[$name])) {
                return $actions[$name];
            }
        }

        return false;
    }
}
