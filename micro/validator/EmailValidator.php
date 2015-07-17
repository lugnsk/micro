<?php /** MicroEmailValidator */

namespace Micro\validator;

use Micro\db\Model;

/**
 * EmailValidator class file.
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
class EmailValidator extends Validator
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
            if (!filter_var($model->$element, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = 'Parameter ' . $element . ' is not a valid E-mail address';

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
        $js = 'if (/^[\w.-]{1,}@[\w.-]{1,}$/.test(this.value) != true) {' .
            ' e.preventDefault(); this.focus(); alert(\'Value is not a valid e-mail\'); }';

        return $js;
    }
}