<?php /** MicroFormModel */

namespace Micro\web;

use Micro\base\Registry;
use Micro\base\Validator;

/**
 * Class FormModel.
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
abstract class FormModel
{
    protected $container;
    public function __construct(Registry $container)
    {
        $this->container = $container;
    }

    /** @var array $errors validation errors */
    protected $errors = [];

    /**
     * Run validation
     *
     * @access public
     *
     * @return bool
     * @throws \Micro\base\Exception
     */
    public function validate()
    {
        foreach ($this->rules() AS $rule) {
            $validator = new Validator(['container'=>$this->container, 'rule'=>$rule]);

            if (!$validator->run($this) AND $validator->errors) {
                $this->errors[] = $validator->errors;
            }
        }
        if ($this->errors) {
            return false;
        }
        return true;
    }

    /**
     * Define rules for validation
     *
     * @access public
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Get client code for validation
     *
     * @access public
     *
     * @return string
     * @throws \Micro\base\Exception
     */
    public function getClient()
    {
        $result = 'jQuery(document).ready(function(){';

        foreach ($this->rules() AS $rule) {
            $validator = new Validator( [ 'container'=>$this->container, 'rule'=>$rule ] );
            if (is_string($js = $validator->run($this, true))) {
                $result .= ' ' . $js;
            }
        }

        return $result . '});';
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
        foreach ($data AS $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Add error model
     *
     * @access public
     *
     * @param string $description error text
     *
     * @return void
     */
    public function addError($description)
    {
        $this->errors[] = $description;
    }

    /**
     * Get errors after validation
     *
     * @access public
     * @return array
     */
    public function getErrors()
    {
        return $this->convertMultiArrayToArray($this->errors);
    }

    /**
     * Convert results array to single array
     *
     * @access protected
     *
     * @param array $errors merge errors
     *
     * @return array
     */
    private function convertMultiArrayToArray($errors)
    {
        static $result = [];
        foreach ($errors AS $error) {
            if (is_array($error)) {
                $this->convertMultiArrayToArray($error);
            } else {
                $result[] = $error;
            }
        }
        return $result;
    }

    /**
     * Get element label
     *
     * @access public
     *
     * @param string $property property name
     *
     * @return null
     */
    public function getLabel($property)
    {
        $elements = $this->attributeLabels();
        return !empty($elements[$property]) ? $elements[$property] : $property;
    }

    /**
     * Define labels for elements
     *
     * @access public
     * @return array
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * Check exists attribute in model
     *
     * @access public
     *
     * @param string $name property name
     *
     * @return bool
     */
    public function checkAttributeExists($name)
    {
        return property_exists($this, $name);
    }
}