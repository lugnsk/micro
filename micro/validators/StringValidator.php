<?php /** MicroStringValidator */

namespace Micro\validators;

use Micro\base\Validator;
use Micro\db\Model;

/**
 * StringValidator class file.
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
class StringValidator extends Validator
{
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
            if (!property_exists($model, $element)) {
                $this->errors[] = 'Parameter ' . $element . ' not defined in class ' . get_class($model);
                return false;
            }
            $elementValue = $model->$element;

            if (isset($this->params['min']) AND !empty($this->params['min'])) {
                if ((integer)$this->params['min'] > strlen($elementValue)) {
                    $this->errors[] = $element . ' error: minimal characters not valid.';
                    return false;
                }
            }
            if (isset($this->params['max']) AND !empty($this->params['max'])) {
                if ((integer)$this->params['max'] < strlen($elementValue)) {
                    $this->errors[] = $element . ' error: maximal characters not valid.';
                    return false;
                }
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
        $object = substr(get_class($model), strrpos(get_class($model), '\\')+1);
        $result = null;

        foreach ($this->elements AS $element) {
            $id = $object . '_' . $element;
            $action = '';

            if (isset($this->params['min'])) {
                $action .= 'if (value.length < '.$this->params['min'].') { /*action*/ }';
            }
            if (isset($this->params['max'])) {
                $action .= ' if (value.length > '.$this->params['max'].') { /*action*/ }';
            }

            $result .= 'jQuery("#'.$id.'").bind("change", function(e){ '.$action.' });'.
                'jQuery("#'.$id.'").bind("submit", function(e){ '.$action.' });';
        }

        return $result;
    }
}