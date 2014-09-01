<?php /** MicroValidator */

namespace Micro\base;

use \Micro\base\Exception;
use \Micro\Micro;

/**
 * Validator is a runner validation process
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class Validator
{
    /** @var array $rule current rule */
    private $rule = [];
    /** @var array $errors errors summary */
    public $errors = [];
    /** @var array $elements validation elements */
    public $elements = [];
    /** @var array $params validation parameters */
    public $params = [];
    /** @var array $validators supported validations */
    protected $validators = [
        'required'  => 'RequiredValidator', // обязательный
        'captcha'   => 'CaptchaValidator', // каптча
        'boolean'   => 'BooleanValidator', // трю ор фелс
        'compare'   => 'CompareValidator', // сверка с ...
        'string'    => 'StringValidator', // строка
        'regexp'    => 'RegexpValidator', // регулярка
        'number'    => 'NumberValidator', // число
        'unique'    => 'UniqueValidator', // уникальность
        'range'     => 'RangeValidator', // от ... до
        'email'     => 'EmailValidator', // емайл
        'url'       => 'UrlValidator', // урл
        'file'      => 'FileValidator', // файл
    ];


    /**
     * Constructor validator object
     *
     * @access public
     * @param array $rule
     * @result void
     */
    public function __construct($rule = [])
    {
        $this->rule = $rule;
    }

    /**
     * Check is empty property
     *
     * @access protected
     * @param $value
     * @param bool $trim
     * @return bool
     */
    protected function isEmpty($value, $trim = false)
    {
        return $value === null || $value === [] || $value === '' || $trim && is_scalar($value) && trim($value) === '';
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
     * Running validation process
     *
     * @access public
     * @param $model
     * @param bool $client
     * @return bool|string
     * @throws Exception
     */
    public function run($model, $client = false)
    {
        $elements = explode(',', strtr(array_shift($this->rule), ' ', ''));
        $name = array_shift($this->rule);
        $className = null;

        if (isset($this->validators[$name])) {
            $className = '\\Micro\\validators\\' . $this->validators[$name];
        } elseif (file_exists(Micro::getInstance()->config['AppDir'] . '/validators/' . $name . '.php')) {
            $className = '\\App\\validators\\' . $name . '.php';
        } else {
            // hook - function as validator
            if (function_exists($name)) {
                foreach ($elements AS $element) {
                    if (property_exists($model, $element)) {
                        $model->$element = call_user_func($name, $model->$element);
                    }
                }
                return true;
            } else {
                throw new Exception('Validator '.$name.' not defined.');
            }
        }

        $valid = new $className;
        $valid->elements = $elements;
        $valid->params = $this->rule;
        if ($client) {
            $result = $valid->client($model);
        } else {
            $result = $valid->validate($model);
        }

        if ($valid->errors) {
            $this->errors[] = $valid->errors;
        }
        return $result;
    }
}