<?php

namespace Micro\mvc;

use Micro\base\Exception;
use Micro\Micro;
use Micro\base\Registry;

abstract class Controller
{
    public $module;

    public function __construct()
    {
        if ($module = Registry::get('request')->getModules()) {
            $app = Micro::getInstance()->config['AppDir'];

            $path = $app . str_replace('\\','/', $module) . '/' .
                ucfirst(basename(str_replace('\\','/', $module))) . 'Module.php';

            if (file_exists($path)) {
                $path = substr(str_replace('/', '\\', str_replace($app, 'App', $path)), 0, -4);
                $this->module = new $path();
            }
        }
    }
    public function action($name = 'index')
    {
        $config = Micro::getInstance()->config;

        $realAction = 'action' . ucfirst($name);
        $actionClass = null;

        if (method_exists($this, 'actions')) {
            $actions = $this->actions();
            if (isset($actions[$name]) AND class_exists($actions[$name])) {
                $actionClass = $actions[$name];
            }
        }

        if (method_exists($this, $realAction)) {
            $actionClass = null;
        } else {
            if (!$actionClass) {
             $error = isset($config['errorController'])?$config['errorController']:'\App\controllers\DefaultController';
             $errorAction = isset($config['errorAction']) ? 'action'.ucfirst($config['errorAction']) : 'actionIndex';

             if (class_exists($error)) {
                 $error = new $error;
                 if (!method_exists($error, $errorAction)) {
                     throw new Exception('Route error');
                 }
                 $error->{'action'}($name);
             }
            }
        }

        // get  filters

        // pre  filter
        // run  action
        // post filter
    }
}