<?php /** MicroForm */

namespace Micro\web;

use Micro\wrappers\Html;

/**
 * Form class file.
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
class Form
{
    /**
     * Render label tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function label($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);

        return Html::label($model->getLabel($property), $element['id'], $options);
    }

    /**
     * Get model field data
     *
     * @access private
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     *
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

    // Fields

    /**
     * Render button field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function buttonField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::buttonField($element['name'], $element['value'], $options);
    }

    /**
     * Render hidden field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function hiddenField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);

        return Html::hiddenField($element['name'], $element['value'], $options);
    }

    /**
     * Render radio field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function radioField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::radioField($element['name'], $element['value'], $options);
    }

    /**
     * Render file field row
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function fileFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->fileField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render file field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function fileField($model, $property, array $options = [])
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
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function imageFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->imageField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render image field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function imageField($model, $property, array $options = [])
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
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function passwordFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->passwordField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render password field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function passwordField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::passwordField($element['name'], $element['value'], $options);
    }

    // HTML5 Fields

    /**
     * Render text field row
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function textFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->textField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render text field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function textField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::textField($element['name'], $element['value'], $options);
    }

    /**
     * Render color field row
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function colorFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->colorField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render color field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function colorField($model, $property, array $options = [])
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
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function dateFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->dateField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render date field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function dateField($model, $property, array $options = [])
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
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function dateTimeFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->dateTimeField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render datetime field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function dateTimeField($model, $property, array $options = [])
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
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function dateTimeLocalFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->dateTimeLocalField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render datetime-local field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function dateTimeLocalField($model, $property, array $options = [])
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
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function emailFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->emailField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render email field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function emailField($model, $property, array $options = [])
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
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function numberFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->numberField($model, $property, $options) .
        Html::closeTag('div');
    }

    // LIST Fields

    /**
     * Render number field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function numberField($model, $property, array $options = [])
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
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function rangeFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->rangeField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render range field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function rangeField($model, $property, array $options = [])
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
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function searchFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->searchField($model, $property, $options) .
        Html::closeTag('div');
    }

    // Rows

    /**
     * Render search field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function searchField($model, $property, array $options = [])
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
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function telFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->telField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render telephone tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function telField($model, $property, array $options = [])
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
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function timeFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->timeField($model, $property, $options) .
        Html::closeTag('div');
    }

    // HTML5 Rows

    /**
     * Render time field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function timeField($model, $property, array $options = [])
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
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function urlFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->urlField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render url field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function urlField($model, $property, array $options = [])
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
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function monthFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->monthField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render moth field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function monthField($model, $property, array $options = [])
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
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function weekFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->weekField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render week field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function weekField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::weekField($element['name'], $element['value'], $options);
    }

    /**
     * Render textArea field row
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function textAreaFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->textAreaField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render textarea tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function textAreaField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::textArea($element['name'], $element['value'], $options);
    }

    /**
     * Render listBox field row
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function listBoxFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->listBoxField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render list box tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attrubtes array
     *
     * @return string
     */
    public function listBoxField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];
        $options['selected'] = $element['value'];

        $elements = [];
        if (!empty($options['elements'])) {
            $elements = $options['elements'];
            unset($options['elements']);
        }

        if (empty($options['size'])) {
            $options['size'] = 3;
        }

        return Html::listBox($element['name'], $elements, $options);
    }

    /**
     * Render text field row
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function dropDownListFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->dropDownListField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render drop down list tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attribute array
     *
     * @return string
     */
    public function dropDownListField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        if (!empty($element['value'])) {
            $options['selected'] = $element['value'];
        }

        $elements = [];
        if (!empty($options['elements'])) {
            $elements = $options['elements'];
            unset($options['elements']);
        }

        return Html::dropDownList($element['name'], $elements, $options);
    }

    public function checkboxFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->checkBoxField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render check box field tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options attributes array
     *
     * @return string
     */
    public function checkBoxField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        return Html::checkBoxField($element['name'], $element['value'], $options);
    }

    // LIST Rows

    /**
     * Render text field row
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options options array
     *
     * @return string
     */
    public function checkBoxListFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->checkBoxListField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render check box list tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property property model
     * @param array $options options array
     *
     * @return string
     */
    public function checkBoxListField($model, $property, array $options = [])
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
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options options array
     *
     * @return string
     */
    public function radioButtonListFieldRow($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $options['id'] = $element['id'];

        $block = [];
        if (!empty($options['block'])) {
            $block = $options['block'];
            unset($options['block']);
        }

        $label = [];
        if (!empty($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        return Html::openTag('div', $block) .
        Html::label($model->getLabel($property), $element['id'], $label) .
        $this->radioButtonListField($model, $property, $options) .
        Html::closeTag('div');
    }

    /**
     * Render radio button list tag
     *
     * @access public
     *
     * @param \Micro\web\FormModel $model model
     * @param string $property model property
     * @param array $options options array
     *
     * @return string
     */
    public function radioButtonListField($model, $property, array $options = [])
    {
        $element = $this->getField($model, $property);
        $radios = !empty($options['radios']) ? $options['radios'] : [];
        $format = !empty($options['format']) ? $options['format'] : '<p>%radio% %text%</p>';

        return Html::radioButtonList($element['name'], $radios, $format, $element['value']);
    }
}