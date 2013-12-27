<?php

namespace Micro\mvc\controllers;

use Micro\Micro;
use Micro\base\Registry;
use Micro\base\Exception;
use Micro\web\Response;


abstract class Controller
{
    /** @var string $module */
    public $module;
    /** @var \Micro\web\Response $response Response HTTP data */
    public $response;


    /**
     * Master action
     *
     * @access public
     *
     * @param string $name Called action name
     *
     * @return string
     * @abstract
     */
    abstract public function action($name = 'index');

    /**
     * Constructor controller
     *
     * @access public
     * @global Registry
     * @result void
     */
    public function __construct()
    {
        // if module defined
        if ($module = Registry::get('request')->getModules()) {
            $app = Micro::getInstance()->config['AppDir'];

            $path = $app . str_replace('\\', '/', $module) . '/' .
                    ucfirst(basename(str_replace('\\', '/', $module))) . 'Module.php';

            // search module class
            if (file_exists($path)) {
                $path = substr(str_replace('/', '\\', str_replace($app, 'App', $path)), 0, -4);
                $this->module = new $path();
            }
        }

        $this->response = new Response;
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
            if (empty($filter['class']) OR !class_exists($filter['class'])) {
                continue;
            }
            if (empty($filter['actions']) OR !in_array($action, $filter['actions'], true)) {
                continue;
            }

            /** @var \Micro\filters\Filter $_filter */
            $_filter = new $filter['class']($action);

            $res = $isPre ? $_filter->pre($filter) : $_filter->post($filter + ['data' => $data]);
            if (!$res) {
                throw new Exception($_filter->result);
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
            if (!empty($actions[$name]) AND class_exists($actions[$name])) {
                return $actions[$name];
            }
        }
        return false;
    }
}