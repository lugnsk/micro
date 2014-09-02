<?php /** MicroFileValidator */

namespace Micro\validators;

use Micro\base\Validator;
use Micro\db\Model;

/**
 * EmailValidator class file.
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
class FileValidator extends Validator
{
    /**
     * Validate on server, make rule
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
        }
        return true;
    }

    /**
     * Client-side validation, make js rule
     *
     * @access public
     * @param Model $model model from elements
     * @return string
     */
    public function client($model)
    {
        $js = '';
        return $js;
    }
}