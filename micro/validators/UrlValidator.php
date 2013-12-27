<?php /** MicroUrlValidator */

namespace Micro\validators;

use Micro\base\Validator;
use Micro\db\Model;

/**
 * UrlValidator class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage validators
 * @version 1.0
 * @since 1.0
 */
class UrlValidator extends Validator
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
            if (filter_var($model->$element, FILTER_VALIDATE_URL) === false) {
                $this->errors[] = 'Parameter ' . $element . ' is not a valid URL address';
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
    public function client($model)
    {
        $js = 'if (/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.test(this.value' .
            ') != true) { e.preventDefault(); this.focus(); alert(\'Value is not a URL\'); }';
        return $js;
    }
}