<?php /** MicroForm */

/**
 * MForm class file.
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
class MForm
{
	/**
	 * Get model field data
	 *
	 * @access private
	 * @param MModel $model
	 * @param string $property
	 * @return array
	 */
	private function getField($model,$property) {
		return array(
			'name'=> get_class($model) . '_' . $property,
			'value'=> (property_exists($model, $property)) ? $model->$property : null
		);
	}
	/**
	 * Render label tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $options
	 * @return string
	 */
	public function label($model,$property, $options = array()) {
		$element = $model->getLabel($property);
		return MHtml::label($element['name'], get_class($model).'_'.$property, $options);
	}
	/**
	 * Render text field tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $options
	 * @return string
	 */
	public function textField($model, $property, $options = array()) {
		$element = $this->getField($model,$property);
		return MHtml::textField($element['name'], $element['value'], $options);
	}
	/**
	 * Render button field tag
	 *
	 * @access public
	 * @param $model
	 * @param $property
	 * @param array $options
	 * @return string
	 */
	public function buttonField($model, $property, $options=array()) {
		$element = $this->getField($model,$property);
		return MHtml::buttonField($element['name'], $element['value'], $options);
	}
	/**
	 * Render check box field tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $options
	 * @return string
	 */
	public function checkBoxField($model, $property, $options=array()) {
		$element = $this->getField($model,$property);
		return MHtml::checkBoxField($element['name'], $element['value'], $options);
	}
	/**
	 * Render file field tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $options
	 * @return string
	 */
	public function fileField($model, $property, $options=array()) {
		$element = $this->getField($model,$property);
		return MHtml::fileField($element['name'], $element['value'], $options);
	}
	/**
	 * Render hidden field tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $options
	 * @return string
	 */
	public function hiddenField($model, $property, $options=array()) {
		$element = $this->getField($model,$property);
		return MHtml::hiddenField($element['name'], $element['value'], $options);
	}
	/**
	 * Render image field tag
	 *
	 * @access public
	 * @param $model
	 * @param $property
	 * @param $imageSource
	 * @param array $options
	 * @return string
	 */
	public function imageField($model, $property, $imageSource, $options=array()) {
		$element = $this->getField($model,$property);
		return MHtml::imageField($element['name'], $element['value'], $imageSource, $options);
	}
	/**
	 * Render password field tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $options
	 * @return string
	 */
	public function passwordField($model, $property, $options=array()) {
		$element = $this->getField($model,$property);
		return MHtml::passwordField($element['name'], $element['value'], $options);
	}
	/**
	 * Render radio field tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $options
	 * @return string
	 */
	public function radioField($model, $property, $options=array()) {
		$element = $this->getField($model,$property);
		return MHtml::radioField($element['name'], $element['value'], $options);
	}
	/**
	 * Render email field tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $options
	 * @return string
	 */
	public function emailField($model, $property, $options=array()) {
		$element = $this->getField($model,$property);
		return MHtml::emailField($element['name'], $element['value'], $options);
	}
	/**
	 * Render range field tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $options
	 * @return string
	 */
	public function rangeField($model, $property, $options=array()) {
		$element = $this->getField($model,$property);
		return MHtml::rangeField($element['name'], $element['value'], $options);
	}
	/**
	 * Render search field tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $options
	 * @return string
	 */
	public function searchField($model, $property, $options=array()) {
		$element = $this->getField($model,$property);
		return MHtml::searchField($element['name'], $element['value'], $options);
	}
	/**
	 * Render telephone tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $options
	 * @return string
	 */
	public function telField($model, $property, $options=array()) {
		$element = $this->getField($model,$property);
		return MHtml::telField($element['name'], $element['value'], $options);
	}
	/**
	 * Render url field tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $options
	 * @return string
	 */
	public function urlField($model, $property, $options=array()) {
		$element = $this->getField($model,$property);
		return MHtml::urlField($element['name'], $element['value'], $options);
	}
	/**
	 * Render textarea tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $options
	 * @return string
	 */
	public function textArea($model, $property, $options=array()) {
		$element = $this->getField($model,$property);
		return MHtml::textArea($element['name'],$element['value'], $options);
	}
	/**
	 * Render drop down list tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $elements
	 * @param array $options
	 * @return string
	 */
	public function dropDownList($model, $property, $elements=array(), $options=array()) {
		$element = $this->getField($model,$property);
		$options['selected'] = $element['value'];
		return MHtml::dropDownList($element['name'], $elements, $options);
	}
	/**
	 * Render list box tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $elements
	 * @param array $options
	 * @return string
	 */
	public function listBox($model, $property, $elements=array(), $options=array()) {
		$element = $this->getField($model,$property);
		$options['selected'] = $element['value'];
		return MHtml::listBox($element['name'], $elements, $options);
	}
	/**
	 * Render check box list tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $checkboxes
	 * @param string $format
	 * @return string
	 */
	public function checkBoxList($model, $property, $checkboxes=array(), $format = '<p>%check% %text%</p>') {
		$element = $this->getField($model, $property);
		return MHtml::checkBoxList($element['name'], $checkboxes, $format, $element['value']);
	}
	/**
	 * Render radio button list tag
	 *
	 * @access public
	 * @param MModel $model
	 * @param string $property
	 * @param array $radios
	 * @param string $format
	 * @return string
	 */
	public function radioButtonList($model, $property, $radios=array(), $format ='<p>%radio% %text%</p>') {
		$element = $this->getField($model, $property);
		return MHtml::radioButtonList($element['name'],$radios, $format, $element['value']);
	}
}