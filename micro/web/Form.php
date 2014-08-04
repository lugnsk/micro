<?php /** MicroForm */

namespace Micro\web;

use Micro\web\helpers\Html;

/**
 * Form class file.
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
class Form
{
    /**
     * Get model field data
     *
     * @access private
     * @param \Micro\db\Model $model
     * @param string $property
     * @return array
     */
    private function getField($model, $property)
    {
        $cl = get_class($model);
        $smallName = substr($cl, strrpos($cl, '\\')+1);
        return [
            'id' => $smallName . '_' . $property,
            'name' => $smallName . '[' . $property . ']',
            'value' => (property_exists($model, $property)) ? $model->$property : null
        ];
    }

    /**
     * Render label tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $options
     * @return string
     */
    public function label($model, $property, $options = [])
    {
        return Html::label($model->getLabel($property), get_class($model) . '_' . $property, $options);
    }

    /**
     * Render text field tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $options
     * @return string
     */
    public function textField($model, $property, $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::textField($element['name'], $element['value'], $options);
    }

    /**
     * Render text field row
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $options
     * @param array $labelOptions
     * @return string
     */
    public function textFieldRow($model, $property, $options = [], $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
            Html::label($model->getLabel($property), $element['id'], $labelOptions) .
            $this->textField($model, $property, $options) .
            Html::closeTag('div');
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
    public function buttonField($model, $property, $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::buttonField($element['name'], $element['value'], $options);
    }

    /**
     * Render check box field tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $options
     * @return string
     */
    public function checkBoxField($model, $property, $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::checkBoxField($element['name'], $element['value'], $options);
    }

    /**
     * Render file field tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $options
     * @return string
     */
    public function fileField($model, $property, $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::fileField($element['name'], $element['value'], $options);
    }

    /**
     * Render hidden field tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $options
     * @return string
     */
    public function hiddenField($model, $property, $options = [])
    {
        $element = $this->getField($model, $property);
        return Html::hiddenField($element['name'], $element['value'], $options);
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
    public function imageField($model, $property, $imageSource, $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::imageField($element['name'], $element['value'], $imageSource, $options);
    }

    /**
     * Render password field tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $options
     * @return string
     */
    public function passwordField($model, $property, $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::passwordField($element['name'], $element['value'], $options);
    }

    /**
     * Render password field row
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $options
     * @param array $labelOptions
     * @return string
     */
    public function passwordFieldRow($model, $property, $options = [], $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->passwordField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render radio field tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $options
     * @return string
     */
    public function radioField($model, $property, $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::radioField($element['name'], $element['value'], $options);
    }

    /**
     * Render email field tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $options
     * @return string
     */
    public function emailField($model, $property, $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::emailField($element['name'], $element['value'], $options);
    }

    /**
     * Render range field tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $options
     * @return string
     */
    public function rangeField($model, $property, $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::rangeField($element['name'], $element['value'], $options);
    }

    /**
     * Render search field tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $options
     * @return string
     */
    public function searchField($model, $property, $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::searchField($element['name'], $element['value'], $options);
    }

    /**
     * Render telephone tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $options
     * @return string
     */
    public function telField($model, $property, $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::telField($element['name'], $element['value'], $options);
    }

    /**
     * Render url field tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $options
     * @return string
     */
    public function urlField($model, $property, $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::urlField($element['name'], $element['value'], $options);
    }

    /**
     * Render textarea tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $options
     * @return string
     */
    public function textArea($model, $property, $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::textArea($element['name'], $element['value'], $options);
    }

    /**
     * Render drop down list tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $elements
     * @param array $options
     * @return string
     */
    public function dropDownList($model, $property, $elements = [], $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        $options['selected'] = $element['value'];
        return Html::dropDownList($element['name'], $elements, $options);
    }

    /**
     * Render list box tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $elements
     * @param array $options
     * @return string
     */
    public function listBox($model, $property, $elements = [], $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        $options['selected'] = $element['value'];
        return Html::listBox($element['name'], $elements, $options);
    }

    /**
     * Render check box list tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $checkboxes
     * @param string $format
     * @return string
     */
    public function checkBoxList($model, $property, $checkboxes = [], $format = '<p>%check% %text%</p>')
    {
        $element = $this->getField($model, $property);
        return Html::checkBoxList($element['name'], $checkboxes, $format, $element['value']);
    }

    /**
     * Render radio button list tag
     *
     * @access public
     * @param \Micro\db\Model $model
     * @param string $property
     * @param array $radios
     * @param string $format
     * @return string
     */
    public function radioButtonList($model, $property, $radios = [], $format = '<p>%radio% %text%</p>')
    {
        $element = $this->getField($model, $property);
        return Html::radioButtonList($element['name'], $radios, $format, $element['value']);
    }
}