<?php /** MicroRequiredValidator */

/**
 * RequiredValidator class file.
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
class RequiredValidator extends \Micro\base\Validator
{
    /**
     * Validate in server
     *
     * @access public
     * @param $model
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

            if (empty($elementValue)) {
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
     * @param $model
     * @return string
     */
    public function client($model)
    {
        $js = '';
        return $js;
    }
}