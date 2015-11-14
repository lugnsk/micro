<?php

namespace Micro\mvc\controllers;

use Micro\base\IContainer;

abstract class RichController extends Controller
{
    /** @var string $format Format for response */
    public $format = 'application/json';

    /**
     * Construct RICH controller
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
        parent::__construct($container, $modules);

        $this->methodType = $this->container->request->getMethod() ?: 'GET';
    }

    /**
     * @inheritdoc
     */
    public function action($name = 'index')
    {
        $actionClass = false;
        if (!method_exists($this, 'action' . ucfirst($name)) && !$actionClass = $this->getActionClassByName($name)) {
            $this->response->setStatus(500, 'Action "' . $name . '" not found into ' . get_class($this));

            return $this->response;
        }

        $types = $this->actionsTypes();
        if (!empty($types[$name]) && $this->methodType !== $types[$name]) {
            $this->response->setStatus(500,
                'Action "' . $name . '" not run with method "' . $this->methodType . '" into ' . get_class($this)
            );

            return $this->response;
        }

        $filters = method_exists($this, 'filters') ? $this->filters() : [];

        $this->applyFilters($name, true, $filters, null);

        $view = null;
        if ($actionClass) {
            /** @var \Micro\mvc\Action $cl */
            $cl = new $actionClass ($this->container);
            $view = $cl->run();
        } else {
            $view = $this->{'action' . ucfirst($name)}();
        }

        if ($this->response->getContentType() !== $this->format) {
            $this->response->setContentType($this->format);
        }

        $this->response->setBody($this->switchContentType($this->applyFilters($name, false, $filters, $view)));

        return $this->response;
    }

    /**
     * Define types for actions
     *
     * <code>
     *  // DELETE, GET, HEAD, OPTIONS, POST, PUT
     * public function actionsTypes() {
     *  return [
     *     'create' => 'POST',
     *     'read'   => 'GET',
     *     'update' => 'UPDATE'
     *     'delete' => 'DELETE'
     *  ];
     * }
     * </code>
     *
     * @access public
     *
     * @return array
     * @abstract
     */
    abstract public function actionsTypes();

    /**
     * Switch content type
     *
     * @access protected
     *
     * @param mixed $data Any content
     *
     * @return string
     */
    protected function switchContentType($data)
    {
        switch ($this->format) {
            case 'application/json':
                $data = json_encode(is_object($data) ? (array)$data : $data);
                break;

            case 'application/xml':
                $data = is_object($data) ? (string)$data : $data;
                break;

            default:
                $data = (string)$data;
        }

        return $data;
    }
}
