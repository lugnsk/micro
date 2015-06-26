<?php /** MicroBooleanValidator */

namespace Micro\validators;

use Micro\base\Validator;
use Micro\base\Registry;
use Micro\db\Model;

/**
 * BooleanValidator class file.
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
class BooleanValidator extends Validator
{
    /**
     * Initial validator
     *
     * @access public
     *
     * @param Registry $registry
     * @param array $rule validation rule
     *
     * @result void
     */
    public function __construct(Registry $registry, array $rule = [])
    {
        parent::__construct($registry, $rule);

        $this->params['true'] = true;
        $this->params['false'] = false;
    }

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
            $elementValue = $model->$element;

            if (($elementValue !== $this->params['true']) AND ($elementValue !== $this->params['false'])) {
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
     * @param Model $model model from elements
     *
     * @return string
     */
    public function client($model)
    {
        return 'if (this.value != ' . $this->params['true'] . ' AND this.value != ' . $this->params['false'] . ') {' .
        ' e.preventDefault(); this.focus(); alert(\'Value not compatible\'); }';
    }
}