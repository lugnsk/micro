<?php /** MicroUrlValidator */

namespace Micro\Validator;

use Micro\Form\IFormModel;

/**
 * UrlValidator class file.
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
class UrlValidator extends BaseValidator
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
            if (filter_var($model->$element, FILTER_VALIDATE_URL) === false) {
                $this->errors[] = 'Parameter ' . $element . ' is not a valid URL address';

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
        $jsString = 'if (/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.test(this.value' .
            ') != true) { e.preventDefault(); this.focus(); alert(\'Value is not a URL\'); }';

        return $jsString;
    }
}
