<?php /** MicroRequiredValidator */

namespace Micro\validator;

use Micro\db\Model;

/**
 * RequiredValidator class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage validator
 * @version 1.0
 * @since 1.0
 */
class RequiredValidator extends BaseValidator implements IValidator
{
    /**
     * Validate on server, make rule
     *
     * @access public
     *
     * @param Model $model checked model
     *
     * @return bool
     */
    public function validate($model)
    {
        foreach ($this->elements AS $element) {
            if (!$model->checkAttributeExists($element)) {
                $this->errors[] = 'Parameter ' . $element . ' not defined in class ' . get_class($model);

                return false;
            }
            if (!$model->$element) {
                $this->errors[] = $element . ' error: required element is empty.';

                return false;
            }
        }

        return true;
    }

    /**
     * Client-side validation, make js rule
     *
     * @access public
     *
     * @param Model $model checked model
     *
     * @return string
     */
    public function client(
        /** @noinspection PhpUnusedParameterInspection */
        $model
    )
    {
        return 'if (!this.value) { e.preventDefault(); this.focus(); alert(\'Required element!\'); }';
    }
}