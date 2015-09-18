<?php /** MicroFormBuilder */

namespace Micro\form;

use Micro\base\Exception;
use Micro\web\Html;
use Micro\widget\FormWidget;

/**
 * Class FormBuilder.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web
 * @version 1.0
 * @since 1.0
 */
class FormBuilder
{
    /** @var FormWidget $widget widget for render */
    protected $widget;
    /** @var Form $form generator for elements */
    protected $form;
    /** @var array $config config array */
    private $config;
    /** @var IFormModel $model model for get data */
    private $model;


    /**
     * Constructor object
     *
     * @access public
     *
     * @param array $config
     * @param IFormModel $model
     * @param string $method method of request
     * @param string $type type data
     * @param string $action path URL action
     * @param array $attr attributes for form
     *
     * @result void
     * @throws \Micro\base\Exception
     */
    public function __construct(
        array $config = [],
        IFormModel $model = null,
        $method = 'GET',
        $type = 'text/plain',
        $action = '',
        array $attr = []
    ) {
        $this->config = $config;
        $this->model = $model;
        $this->widget = new FormWidget([
            'action' => $action,
            'method' => $method,
            'type' => $type,
            'client' => $model->getClient(),
            'attributes' => $attr
        ]);
    }

    /**
     * Set model data
     *
     * Loading data in model from array
     *
     * @access public
     *
     * @param array $data array to change
     *
     * @return void
     */
    public function setModelData(array $data = [])
    {
        $this->model->setModelData($data);
    }

    /**
     * Validation model
     *
     * @access public
     *
     * @return bool
     * @throws \Micro\base\Exception
     */
    public function validateModel()
    {
        return $this->model->validate();
    }

    /**
     * Getting model
     *
     * @access public
     * @return \Micro\mvc\models\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Convert object to string
     *
     * @access public
     * @return string
     * @throws \Micro\base\Exception
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Render form builder
     *
     * @access public
     * @return string
     */
    public function render()
    {
        ob_start();

        $this->beginRender();
        try {
            $this->contentRender();
        } catch (Exception $e) {
            //
        }
        $this->endRender();

        return ob_get_clean();
    }

    /**
     * Render form heading
     *
     * @access public
     * @return void
     */
    public function beginRender()
    {
        $this->form = $this->widget->init();
        if (!empty($this->config['legend'])) {
            echo Html::openTag('fieldset');
            echo Html::legend($this->config['legend']);
        }
        if (!empty($this->config['description'])) {
            echo Html::openTag('div',
                ['class' => 'description']), $this->config['description'], Html::closeTag('div');
        }
        if ($this->model) {
            $errors = $this->getModelErrors();
            if ($errors) {
                echo Html::openTag('div', ['class' => 'errors']);
                foreach ($errors AS $error) {
                    echo Html::openTag('div', ['class' => 'error']), $error, Html::closeTag('div');
                }
                echo Html::closeTag('div');
            }
        }
    }

    /**
     * Get errors from model
     *
     * @access public
     * @return array
     */
    public function getModelErrors()
    {
        return $this->model->getErrors();
    }

    /**
     * Render form elements
     *
     * @access public
     *
     * @param null|array $conf configuration array
     *
     * @return void
     * @throws \Micro\base\Exception
     */
    public function contentRender($conf = null)
    {
        if (!$conf) {
            $conf = $this->config;
        }
        foreach ($conf['elements'] AS $key => $value) {
            if (is_array($conf['elements'][$key])) {
                if ($value['type'] === 'form') {
                    $subForm = new FormBuilder($value, !empty($value['model']) ? $value['model'] : null);
                    echo $subForm;
                } elseif ($this->model) {
                    echo $this->form->$value['type']($this->model, $key,
                        !empty($value['options']) ? $value['options'] : []);
                } else {
                    echo Html::$value['type']($key, $value['value'], $value['options']);
                }
            } else {
                echo $conf['elements'][$key];
            }
        }
        foreach ($this->config['buttons'] AS $button) {
            $type = $button['type'] . 'Button';
            echo Html::$type($button['label'], !empty($button['options']) ? $button['options'] : []);
        }
    }

    /**
     * Finish form render
     *
     * @access public
     * @return void
     */
    public function endRender()
    {
        if (!empty($this->config['legend'])) {
            echo Html::closeTag('fieldset');
        }
        $this->widget->run();
    }
}
