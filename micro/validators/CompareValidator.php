<?php /** MicroCompareValidator */

namespace Micro\validators;

use Micro\base\Validator;
use Micro\db\Model;

/**
 * CompareValidator class file.
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
class CompareValidator extends Validator
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
            return false;
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