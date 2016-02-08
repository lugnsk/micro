<?php /** MicroFormModel */

namespace Micro\Form;

use Micro\Base\IContainer;
use Micro\Validator\Validator;

/**
 * Class FormModel.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Form
 * @version 1.0
 * @since 1.0
 */
abstract class FormModel implements IFormModel
{
    /** @var IContainer $container */
    protected $container;
    /** @var array $errors validation errors */
    protected $errors = [];


    /**
     * Constructor form
     *
     * @access public
     *
     * @param IContainer $container
     *
     * @result void
     */
    public function __construct(IContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function validate()
    {
        foreach ($this->rules() AS $rule) {
            $validator = new Validator(['container' => $this->container, 'rule' => $rule]);

            if (!$validator->run($this) && (0 < count($validator->errors))) {
                $this->errors[] = $validator->errors;
            }
        }
        if (count($this->errors)) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getClient()
    {
        $result = 'jQuery(document).ready(function(){';

        foreach ($this->rules() AS $rule) {
            $validator = new Validator(['container' => $this->container, 'rule' => $rule]);
            if (is_string($js = $validator->run($this, true))) {
                $result .= ' ' . $js;
            }
        }

        return $result . '});';
    }

    /**
     * @inheritdoc
     */
    public function setModelData(array $data = [])
    {
        foreach ($data AS $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function addError($description)
    {
        $this->errors[] = $description;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function getLabel($property)
    {
        $elements = $this->attributeLabels();

        return !empty($elements[$property]) ? $elements[$property] : $property;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function checkAttributeExists($name)
    {
        return property_exists($this, $name);
    }
}
