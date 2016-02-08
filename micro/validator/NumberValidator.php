<?php /** MicroNumberValidator */

namespace Micro\Validator;

use Micro\Form\IFormModel;

/**
 * NumberValidator class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Validator
 * @version 1.0
 * @since 1.0
 */
class NumberValidator extends BaseValidator
{
    /**
     * @inheritdoc
     */
    public function validate(IFormModel $model)
    {
        foreach ($this->elements AS $element) {
            if (!$model->checkAttributeExists($element)) {
                $this->errors[] = 'Parameter ' . $element . ' not defined in class ' . get_class($model);

                return false;
            }
            if (!is_numeric($model->$element)) {
                $this->errors[] = 'Parameter ' . $element . ' is not a numeric';
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function client(IFormModel $model)
    {
        return 'if (! ((this.value ^ 0) === this.value) ) { e.preventDefault(); this.focus(); alert(\'Value is not number\'); }';
    }
}
