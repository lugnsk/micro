<?php /** MicroForm */

namespace Micro\Form;

use Micro\Web\Html;

/**
 * Form class file.
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
class Form
{
    /**
     * Render label tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function label(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);

        return Html::label($model->getLabel($property), $element['id'], $options);
    }

    /**
     * Get model field data
     *
     * @access private
     *
     * @param IFormModel $model model
     * @param string $property model property
     *
     * @return array
     */
    protected function getField(IFormModel $model, $property)
    {
        $cl = get_class($model);
        $smallName = substr($cl, strrpos($cl, '\\') + 1);

        return [
            'id' => $smallName . '_' . $property,
            'name' => $smallName . '[' . $property . ']',
            'value' => (property_exists($model, $property)) ? $model->$property : null
        ];
    }

    /**
     * Render hidden field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function hiddenField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);

        return Html::hiddenField($element['name'], $element['value'], $options);
    }

    /**
     * Render button field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function buttonField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::buttonField($element['name'], $element['value'], $options);
    }

    /**
     * Render radio field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function radioField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::radioField($element['name'], $element['value'], $options);
    }

    /**
     * Render text field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function textFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->textField($model, $property, $options) .
        Html::closeTag('div');
    }

    // Fields

    /**
     * Get block from options
     *
     * @access protected
     *
     * @param string $name Block name
     * @param array $options Options array
     *
     * @return array
     */
    protected function getBlock($name, array &$options)
    {
        $block = [];
        if (!empty($options[$name])) {
            $block = $options[$name];
            unset($options[$name]);
        }

        return $block;
    }

    /**
     * Render text field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function textField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::textField($element['name'], $element['value'], $options);
    }

    /**
     * Render file field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function fileFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->fileField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render file field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function fileField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::fileField($element['name'], $element['value'], $options);
    }

    /**
     * Render image field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function imageFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->imageField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render image field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function imageField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        $image = !empty($options['image']) ? $options['image'] : [];

        return Html::imageField($element['name'], $element['value'], $image, $options);
    }

    /**
     * Render password field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function passwordFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->passwordField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render password field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function passwordField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::passwordField($element['name'], $element['value'], $options);
    }

    /**
     * Render textArea field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function textAreaFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->textAreaField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render textarea tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function textAreaField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::textArea($element['name'], $element['value'], $options);
    }

    /**
     * Render checkbox field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function checkboxFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->checkBoxField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render check box field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function checkBoxField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::checkBoxField($element['name'], $element['value'], $options);
    }

    // Lists

    /**
     * Render listBox field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function listBoxFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->listBoxField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render list box tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function listBoxField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        $options['selected'] = $element['value'];

        if (empty($options['size'])) {
            $options['size'] = 3;
        }

        return Html::listBox($element['name'], $this->getBlock('elements', $options), $options);
    }

    /**
     * Render text field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function dropDownListFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->dropDownListField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render drop down list tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function dropDownListField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        if (!empty($element['value'])) {
            $options['selected'] = $element['value'];
        }

        return Html::dropDownList($element['name'], $this->getBlock('elements', $options), $options);
    }

    /**
     * Render text field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options options array
     *
     * @return string
     */
    public function checkBoxListFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->checkBoxListField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render check box list tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property property model
     * @param array $options options array
     *
     * @return string
     */
    public function checkBoxListField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $checkboxes = !empty($options['checkboxes']) ? $options['checkboxes'] : [];
        $format = !empty($options['format']) ? $options['format'] : '<p>%check% %text%</p>';

        return Html::checkBoxList($element['name'], $checkboxes, $format, $element['value']);
    }

    /**
     * Render text field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options options array
     *
     * @return string
     */
    public function radioButtonListFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->radioButtonListField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render radio button list tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options options array
     *
     * @return string
     */
    public function radioButtonListField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $radios = !empty($options['radios']) ? $options['radios'] : [];
        $format = !empty($options['format']) ? $options['format'] : '<p>%radio% %text%</p>';

        return Html::radioButtonList($element['name'], $radios, $format, $element['value']);
    }

    // HTML5 Fields

    /**
     * Render color field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function colorFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->colorField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render color field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function colorField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::colorField($element['name'], $element['value'], $options);
    }

    /**
     * Render number field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function dateFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->dateField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render date field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function dateField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::dateField($element['name'], $element['value'], $options);
    }

    /**
     * Render datetime field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function dateTimeFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->dateTimeField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render datetime field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function dateTimeField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::datetimeField($element['name'], $element['value'], $options);
    }

    /**
     * Render datetime-local field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function dateTimeLocalFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->dateTimeLocalField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render datetime-local field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function dateTimeLocalField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::datetimeLocalField($element['name'], $element['value'], $options);
    }

    /**
     * Render email field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function emailFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->emailField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render email field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function emailField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::emailField($element['name'], $element['value'], $options);
    }

    /**
     * Render number field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function numberFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->numberField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render number field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function numberField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::numberField($element['name'], $element['value'], $options);
    }

    /**
     * Render range field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function rangeFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->rangeField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render range field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function rangeField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::rangeField($element['name'], $element['value'], $options);
    }

    /**
     * Render search field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function searchFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->searchField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render search field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function searchField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::searchField($element['name'], $element['value'], $options);
    }

    /**
     * Render telephone field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function telFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->telField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render telephone tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function telField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::telField($element['name'], $element['value'], $options);
    }

    /**
     * Render time field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function timeFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->timeField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render time field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function timeField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::timeField($element['name'], $element['value'], $options);
    }

    /**
     * Render url field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function urlFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->urlField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render url field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function urlField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::urlField($element['name'], $element['value'], $options);
    }

    /**
     * Render month field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function monthFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->monthField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render moth field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function monthField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::monthField($element['name'], $element['value'], $options);
    }

    /**
     * Render week field row
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function weekFieldRow(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', $this->getBlock('block', $options)) .
        Html::label($model->getLabel($property), $element['id'], $this->getBlock('label', $options)) .
        $this->weekField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render week field tag
     *
     * @access public
     *
     * @param IFormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function weekField(IFormModel $model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::weekField($element['name'], $element['value'], $options);
    }
}
