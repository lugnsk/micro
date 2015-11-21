<?php /** MicroValidator */

namespace Micro\validator;

use Micro\base\Exception;
use Micro\base\IContainer;
use Micro\form\IFormModel;

/**
 * Validator is a runner validation process
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage
 * @version 1.0
 * @since 1.0
 */
class Validator
{
    /** @var array $validators supported validations */
    protected static $validators = [
        'required' => 'RequiredValidator',
        'captcha'  => 'CaptchaValidator',
        'boolean'  => 'BooleanValidator',
        'compare'  => 'CompareValidator',
        'string'   => 'StringValidator',
        'regexp'   => 'RegexpValidator',
        'number'   => 'NumberValidator',
        'unique'   => 'UniqueValidator',
        'range'    => 'RangeValidator',
        'email'    => 'EmailValidator',
        'url'      => 'UrlValidator',
        'file'     => 'FileValidator'
    ];
    /** @var array $errors errors summary */
    public $errors = [];
    /** @var array $elements validation elements */
    public $elements = [];
    /** @var array $params validation parameters */
    public $params = [];
    /** @var IContainer $container Container */
    protected $container;
    /** @var array $rule current rule */
    protected $rule = [];

    /**
     * Constructor validator object
     *
     * @access public
     *
     * @param array $params configuration array
     *
     * @result void
     */
    public function __construct(array $params)
    {
        $this->container = $params['container'];
        $this->rule = $params['rule'];
    }

    /**
     * Running validation process
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param bool $client run on client side?
     *
     * @return bool|string
     * @throws Exception
     */
    public function run($model, $client = false)
    {
        $elements = explode(',', str_replace(' ', '', array_shift($this->rule)));
        $name = array_shift($this->rule);

        $className = $this->getValidatorClass($name);
        if (!$className) {
            if (function_exists($name)) {
                foreach ($elements AS $element) {
                    if (property_exists($model, $element)) {
                        $model->$element = call_user_func($name, $model->$element);
                    }
                }

                return true;
            } elseif (method_exists($model, $name)) {
                foreach ($elements AS $element) {
                    if (property_exists($model, $element)) {
                        $model->$element = call_user_func([$model, $name], $model->$element);
                    }
                }

                return true;
            } else {
                throw new Exception('Validator ' . $name . ' not defined.');
            }
        }

        /** @var IValidator $valid */
        $valid = new $className(['container' => $this->container, 'params' => $this->rule]);
        $valid->elements = $elements;

        if ($client && method_exists($valid, 'client')) {
            $result = $this->clientValidate($valid, $model);
        } else {
            $result = $valid->validate($model);
        }

        if ($valid->errors) {
            $this->errors[] = $valid->errors;
        }

        return $result;
    }

    /**
     * @param $name
     * @return bool|string
     */
    protected function getValidatorClass($name)
    {
        if (!empty(self::$validators[$name])) {
            return '\\Micro\\validator\\' . self::$validators[$name];
        } elseif (class_exists($name) && is_subclass_of($name, '\Micro\validator\IValidator')) {
            return $name;
        } elseif (file_exists($this->container->kernel->getAppDir() . '/validator/' . $name . '.php')) {
            return '\\App\\validator\\' . $name;
        }

        return false;
    }

    /**
     * Client validation making
     *
     * @access public
     *
     * @param IValidator $validator
     * @param IFormModel $model
     *
     * @return string
     */
    public function clientValidate(IValidator $validator, IFormModel $model)
    {
        $object = substr(get_class($model), strrpos(get_class($model), '\\') + 1);

        $result = null;
        foreach ($validator->elements AS $element) {
            $id = $object . '_' . $element;
            /** @noinspection PhpUndefinedMethodInspection */
            $result .= 'jQuery("#' . $id . '").bind("change blur submit", function(e){ ';
            /** @noinspection DisconnectedForeachInstructionInspection */
            $result .= $validator->client($model);
            $result .= ' });';
        }

        return $result;
    }

    /**
     * Get errors after run validation
     *
     * @access public
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Check is empty property
     *
     * @access protected
     *
     * @param mixed $value check value is empty
     * @param bool $trim run trim?
     *
     * @return bool
     */
    protected function isEmpty($value, $trim = false)
    {
        return $value === null || $value === [] || $value === '' || $trim && is_scalar($value) && trim($value) === '';
    }
}
