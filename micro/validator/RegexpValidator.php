<?php /** MicroRegexpValidator */

namespace Micro\Validator;

use Micro\Form\IFormModel;

/**
 * RegexpValidator class file.
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
class RegexpValidator extends BaseValidator
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
            $elementValue = $model->$element;
            if (preg_match($this->params['pattern'], $elementValue) === false) {
                $this->errors[] = 'Parameter ' . $element . ' not valid with regular expression';

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
        $js = 'if (!this.value.match(' . $this->params['pattern'] . ')) {' .
            ' e.preventDefault(); this.focus(); alert(\'Value not valid with regular expression\'); }';

        return $js;
    }
}
