<?php /** MicroBooleanValidator */

namespace Micro\validators;

use Micro\base\Validator;
use Micro\db\Model;

/**
 * BooleanValidator class file.
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
class BooleanValidator extends Validator
{
    /**
     * Initial validator
     *
     * @access public
     * @param array $rule
     * @result void
     */
    public function __construct($rule=[])
    {
        parent::__construct($rule);

        $this->params['true'] = true;
        $this->params['false'] = false;
    }

    /**
     * Validate in server
     *
     * @access public
     * @param Model $model
     * @return bool
     */
    public function validate($model)
    {
        foreach ($this->elements AS $element) {
            if (!method_exists($model, $element)) {
                $this->errors[] = 'Parameter ' . $element . ' not defined in class ' . get_class($model);
                return false;
            }
            $elementValue = $model->$element;

            if (($elementValue !== $this->params['true']) AND ($elementValue !== $this->params['false'])) {
                $this->errors[] = $element . ' error: required element is empty.';
                return false;
            }
        }
        return true;
    }

    /**
     * Validate in client
     *
     * @access public
     * @param Model $model
     * @return string
     */
    public function client($model)
    {
        return 'if (this.value != '.$this->params['true'].' AND this.value != '.$this->params['false'].') { alert(\'Value not compatible\'); }';
    }
}