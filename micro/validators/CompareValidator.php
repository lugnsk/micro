<?php /** MicroCompareValidator */

namespace Micro\validators;

use Micro\base\Validator;
use Micro\db\Model;

/**
 * CompareValidator class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage validators
 * @version 1.0
 * @since 1.0
 */
class CompareValidator extends Validator
{
    /**
     * Initial validator
     *
     * @access public
     * @param array $rule validation rule
     * @result void
     */
    public function __construct($rule = [])
    {
        parent::__construct($rule);

        $this->params['attribute'] = null;
        $this->params['value'] = null;
    }

    /**
     * Validate on server, make rule
     *
     * @access public
     * @param Model $model checked model
     * @return bool
     */
    public function validate($model)
    {
        if (!$this->params['attribute'] AND !$this->params['value']) {
            return false;
        }

        foreach ($this->elements AS $element) {
            if (!property_exists($model, $element)) {
                $this->errors[] = 'Parameter ' . $element . ' not defined in class ' . get_class($model);
                return false;
            }
            $elementValue = $model->$element;
            if ($this->params['value'] AND ($this->params['value'] != $elementValue)) {
                $this->errors[] = 'Parameter ' . $element . ' not equal ' . $this->params['value'];
                return false;
            } elseif ($this->params['attribute'] AND ($model->{$this->params['attribute']} != $elementValue)) {
                $this->errors[] = 'Parameter ' . $element . ' not equal ' . $model->{$this->params['attribute']};
                return false;
            }
        }
        return true;
    }

    /**
     * Client-side validation, make js rule
     *
     * @access public
     * @param Model $model checked model
     * @return string
     */
    public function client($model)
    {
        $value = $this->params['value'];
        if (!$value) {
            $attribute = $this->params['attribute'];
            $value = $model->$attribute;
        }

        $js = 'if (this.value!="' . $value . '") { e.preventDefault(); this.focus(); alert(\'Value is not compatible\'); }';
        return $js;
    }
}