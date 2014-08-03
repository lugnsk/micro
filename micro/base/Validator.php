<?php /** MicroValidator */

namespace Micro\base;

use Micro\Micro;

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
    /** @property array $rule */
    private $rule = [];
    /** @property array $errors */
    public $errors = [];
    /** @property array $elements */
    public $elements = [];
    /** @property array $params */
    public $params = [];
    /** @property array $validators */
    protected $validators = [
        'string'    => 'StringValidator',
        'required'  => 'RequiredValidator',
        'boolean'   => 'BooleanValidator',
        //		'filter'=>'MFilterValidator',
        //		'match'=>'MRegularExpressionValidator',
        //		'email'=>'MEmailValidator',
        //		'url'=>'MUrlValidator',
        //		'unique'=>'MUniqueValidator',
        //		'compare'=>'MCompareValidator',
        //		'in'=>'MRangeValidator',
        //		'numerical'=>'MNumberValidator',
        //		'captcha'=>'MCaptchaValidator',
        //		'type'=>'MTypeValidator',
        //		'file'=>'MFileValidator',
        //		'default'=>'MDefaultValueValidator',
        //		'exist'=>'MExistValidator',
        //		'safe'=>'MSafeValidator',
        //		'unsafe'=>'MUnsafeValidator',
        //		'date'=>'MDateValidator',
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
     */
    public function run($model, $client = false)
    {
        $elements = explode(',', str_replace(' ', '', array_shift($this->rule)));
        $name = array_shift($this->rule);

        $filename = false;
        if (isset($this->validators[$name])) {
            $filename = Micro::getInstance()->config['MicroDir'] . '/validators/' . $this->validators[$name] . '.php';
        } elseif (file_exists(Micro::getInstance()->config['AppDir'] . '/validators/' . $name . '.php')) {
            $filename = Micro::getInstance()->config['AppDir'] . '/validators/' . $name . '.php';
        }

        if ($filename) {
            include_once $filename;
        } else {
            if (function_exists($name)) {
                foreach ($elements AS $element) {
                    if (property_exists($model, $element)) {
                        $model->$element = call_user_func($name, $model->$element);
                    }
                }
                return true;
            } else {
                throw new \Micro\base\Exception('Validator '.$name.' not defined.');
            }
        }

        $valid = new $this->validators[$name];
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