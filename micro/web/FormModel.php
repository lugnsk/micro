<?php /** MicroFormModel */

namespace Micro\web;

use Micro\base\Validator;

/**
 * Class FormModel.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web
 * @version 1.0
 * @since 1.0
 */
abstract class FormModel
{
    /** @var array $errors validation errors */
    protected $errors = [];


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
     * Run validation
     *
     * @access public
     * @return bool
     */
    public function validate()
    {
        foreach ($this->rules() AS $rule) {
            $validator = new Validator($rule);

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
     * Get client code for validation
     * @return string
     */
    public function getClient()
    {
        $result = 'jQuery(document).ready(function(){';

        foreach ($this->rules() AS $rule) {
            $validator = new Validator($rule);
            if (is_string($js = $validator->run($this,true))) {
                $result .= ' '.$js;
            }
        }

        return $result.'});';
    }

    /**
     * Set model data
     *
     * Loading data in model from array
     *
     * @access public
     * @param array $data
     * @return void
     */
    public function setModelData($data = [])
    {
        foreach ($data AS $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Add error model
     *
     * @access public
     * @param string $description
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
     * @param array $errors
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
     * Get element label
     *
     * @access public
     * @param $property
     * @return null
     */
    public function getLabel($property)
    {
        $elements = $this->attributeLabels();
        return (isset($elements[$property])) ? $elements[$property] : null;
    }
}