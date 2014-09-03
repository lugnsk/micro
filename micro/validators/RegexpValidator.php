<?php /** MicroRegexpValidator */

namespace Micro\validators;

use Micro\base\Validator;
use Micro\db\Model;

/**
 * RegexpValidator class file.
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
class RegexpValidator extends Validator
{
    /**
     * Validate on server, make rule
     *
     * @access public
     * @param Model $model checked model
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
            if (preg_match($this->params['pattern'], $elementValue) === FALSE) {
                $this->errors[] = 'Parameter ' . $element . ' not valid with regular expression';
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
        $js = 'if (!this.value.match('.$this->params['pattern'].')) {'.
            ' e.preventDefault(); this.focus(); alert(\'Value not valid with regular expression\'); }';
        return $js;
    }
}