<?php /** MicroForm */

namespace Micro\web;

use Micro\wrappers\Html;

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
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @return array
     */
    private function getField($model, $property)
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
     * Render label tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function label($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        return Html::label($model->getLabel($property), $element['id'], $options);
    }

    // Fields

    /**
     * Render button field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function buttonField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::buttonField($element['name'], $element['value'], $options);
    }

    /**
     * Render check box field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function checkBoxField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::checkBoxField($element['name'], $element['value'], $options);
    }

    /**
     * Render file field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function fileField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::fileField($element['name'], $element['value'], $options);
    }

    /**
     * Render hidden field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function hiddenField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        return Html::hiddenField($element['name'], $element['value'], $options);
    }

    /**
     * Render image field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param string $imageSource path to image
     * @param array $options attributes array
     * @return string
     */
    public function imageField($model, $property, $imageSource, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::imageField($element['name'], $element['value'], $imageSource, $options);
    }

    /**
     * Render password field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function passwordField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::passwordField($element['name'], $element['value'], $options);
    }

    /**
     * Render radio field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function radioField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::radioField($element['name'], $element['value'], $options);
    }

    /**
     * Render text field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function textField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::textField($element['name'], $element['value'], $options);
    }

    /**
     * Render textarea tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function textAreaField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::textArea($element['name'], $element['value'], $options);
    }

    // HTML5 Fields

    /**
     * Render color field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function colorField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::colorField($element['name'], $element['value'], $options);
    }

    /**
     * Render date field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function dateField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::dateField($element['name'], $element['value'], $options);
    }

    /**
     * Render datetime field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function dateTimeField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::datetimeField($element['name'], $element['value'], $options);
    }

    /**
     * Render datetime-local field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function dateTimeLocalField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::datetimeLocalField($element['name'], $element['value'], $options);
    }

    /**
     * Render email field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function emailField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::emailField($element['name'], $element['value'], $options);
    }

    /**
     * Render number field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function numberField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::numberField($element['name'], $element['value'], $options);
    }

    /**
     * Render range field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function rangeField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::rangeField($element['name'], $element['value'], $options);
    }

    /**
     * Render search field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function searchField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::searchField($element['name'], $element['value'], $options);
    }

    /**
     * Render telephone tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function telField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::telField($element['name'], $element['value'], $options);
    }

    /**
     * Render time field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function timeField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::timeField($element['name'], $element['value'], $options);
    }

    /**
     * Render url field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function urlField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::urlField($element['name'], $element['value'], $options);
    }

    /**
     * Render moth field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function monthField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::monthField($element['name'], $element['value'], $options);
    }

    /**
     * Render week field tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     * @return string
     */
    public function weekField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        return Html::weekField($element['name'], $element['value'], $options);
    }

    // LIST Fields

    /**
     * Render list box tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $elements elements array
     * @param array $options attrubtes array
     * @return string
     */
    public function listBoxField($model, $property, array $elements = [], array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        $options['selected'] = $element['value'];
        return Html::listBox($element['name'], $elements, $options);
    }

    /**
     * Render drop down list tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $elements elements array
     * @param array $options attribute array
     * @return string
     */
    public function dropDownListField($model, $property, array $elements = [], array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        $options['selected'] = $element['value'];
        return Html::dropDownList($element['name'], $elements, $options);
    }

    /**
     * Render check box list tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property property model
     * @param array $checkboxes checkBoxes array
     * @param string $format format for render
     * @return string
     */
    public function checkBoxListField($model, $property, array $checkboxes = [], $format = '<p>%check% %text%</p>')
    {
        $element = $this->getField($model, $property);
        return Html::checkBoxList($element['name'], $checkboxes, $format, $element['value']);
    }

    /**
     * Render radio button list tag
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $radios radios array
     * @param string $format format for render
     * @return string
     */
    public function radioButtonListField($model, $property, array $radios = [], $format = '<p>%radio% %text%</p>')
    {
        $element = $this->getField($model, $property);
        return Html::radioButtonList($element['name'], $radios, $format, $element['value']);
    }

    // Rows

    /**
     * Render file field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function fileFieldRow($model, $property, array $options = [], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->fileField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render image field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param string $imageSource path to image
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function imageFieldRow($model, $property, $imageSource, array $options = [], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->imageField($model, $property, $imageSource, $options) .
        Html::closeTag('div');
    }

    /**
     * Render password field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function passwordFieldRow($model, $property, array $options = [], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->passwordField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render text field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function textFieldRow($model, $property, array $options = [], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->textField($model, $property, $options) .
        Html::closeTag('div');
    }

    // HTML5 Rows

    /**
     * Render color field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function colorFieldRow($model, $property, array $options=[], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class'=>'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->colorField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render number field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function dateFieldRow($model, $property, array $options = [], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->dateField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render datetime field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function dateTimeFieldRow($model, $property, array $options = [], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->dateTimeField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render datetime-local field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function dateTimeLocalFieldRow($model, $property, array $options = [], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->dateTimeLocalField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render email field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function emailFieldRow($model, $property, array $options = [], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->emailField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render number field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function numberFieldRow($model, $property, array $options = [], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->numberField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render range field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function rangeFieldRow($model, $property, array $options = [], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->rangeField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render search field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function searchFieldRow($model, $property, array $options=[], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class'=>'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->searchField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render telephone field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function telFieldRow($model, $property, array $options = [], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->telField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render time field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function timeFieldRow($model, $property, array $options = [], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->timeField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render url field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function urlFieldRow($model, $property, array $options = [], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->urlField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render month field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function monthFieldRow($model, $property, array $options = [], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->monthField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render week field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function weekFieldRow($model, $property, array $options = [], array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->weekField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render textArea field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function textAreaFieldRow($model, $property, array $options=[], array $labelOptions=[])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class'=>'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->textAreaField($model, $property, $options).
        Html::closeTag('div');
    }

    // LIST Rows

    /**
     * Render listBox field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $elements elements for listBox
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function listBoxFieldRow($model, $property, array $elements=[], array $options=[], array $labelOptions=[])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->listBoxField($model, $property, $elements, $options) .
        Html::closeTag('div');
    }

    /**
     * Render text field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $elements elements for listBox
     * @param array $options attribute array
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function dropDownListFieldRow($model, $property, array $elements=[], array $options=[], array $labelOptions=[])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->dropDownListField($model, $property, $elements, $options) .
        Html::closeTag('div');
    }

    /**
     * Render text field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $checkboxes checkboxes for list
     * @param string $format template for render
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function checkBoxListFieldRow($model, $property, array $checkboxes=[], $format = '<p>%check% %text%</p>', array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->checkBoxListField($model, $property, $checkboxes, $format, $options) .
        Html::closeTag('div');
    }

    /**
     * Render text field row
     *
     * @access public
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $radios radios for list
     * @param string $format template for render
     * @param array $labelOptions attribute array for label
     * @return string
     */
    public function radioButtonListFieldRow($model, $property, array $radios = [], $format = '<p>%radio% %text%</p>', array $labelOptions = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::openTag('div', ['class' => 'row']) .
        Html::label($model->getLabel($property), $element['id'], $labelOptions) .
        $this->radioButtonListField($model, $property, $radios, $format) .
        Html::closeTag('div');
    }
}