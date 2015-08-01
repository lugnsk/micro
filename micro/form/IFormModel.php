<?php

namespace Micro\form;

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
interface IFormModel
{
    /**
     * Run validation
     *
     * @access public
     *
     * @return bool
     * @throws \Micro\base\Exception
     */
    public function validate();

    /**
     * Define rules for validation
     *
     * @access public
     * @return array
     */
    public function rules();

    /**
     * Get client code for validation
     *
     * @access public
     *
     * @return string
     * @throws \Micro\base\Exception
     */
    public function getClient();

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
    public function setModelData(array $data = []);

    /**
     * Add error model
     *
     * @access public
     *
     * @param string $description error text
     *
     * @return void
     */
    public function addError($description);

    /**
     * Get errors after validation
     *
     * @access public
     * @return array
     */
    public function getErrors();

    /**
     * Get element label
     *
     * @access public
     *
     * @param string $property property name
     *
     * @return null
     */
    public function getLabel($property);

    /**
     * Define labels for elements
     *
     * @access public
     * @return array
     */
    public function attributeLabels();

    /**
     * Check exists attribute in model
     *
     * @access public
     *
     * @param string $name property name
     *
     * @return bool
     */
    public function checkAttributeExists($name);
}
