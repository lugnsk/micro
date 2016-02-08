<?php /** MicroEmailValidator */

namespace Micro\Validator;

use Micro\Form\IFormModel;

/**
 * EmailValidator class file.
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
class EmailValidator extends BaseValidator
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
            if (!filter_var($model->$element, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = 'Parameter ' . $element . ' is not a valid E-mail address';

                return false;
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function client(IFormModel $model)
    {
        $js = 'if (/^[\w.-]{1,}@[\w.-]{1,}$/.test(this.value) != true) {' .
            ' e.preventDefault(); this.focus(); alert(\'Value is not a valid e-mail\'); }';

        return $js;
    }
}
