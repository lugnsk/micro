<?php /** MicroRangeValidator */

namespace Micro\validators;

use Micro\base\Validator;
use Micro\db\Model;

/**
 * RangeValidator class file.
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
class RangeValidator extends Validator
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
        if (!isset($this->params['min']) OR empty($this->params['min'])) {
            $this->errors[] = 'Minimal value not declared to Range validator';
        }
        if (!isset($this->params['max']) OR empty($this->params['max'])) {
            $this->errors[] = 'Maximal value not declared to Range validator';
        }
        $step = (isset($this->params['step']) AND !empty($this->params['step'])) ? $this->params['step'] : 1;

        $rang = range($this->params['min'], $this->params['max'], $step);

        foreach ($this->elements AS $element) {
            if (!property_exists($model, $element)) {
                $this->errors[] = 'Parameter ' . $element . ' not defined in class ' . get_class($model);
                return false;
            }
            if (!in_array($model->$element, $rang)) {
                $this->errors[] = 'Parameter ' . $element . ' not find in rage ' .
                    $this->params['min'] . '..' . $this->params['max'];
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
        $js = 'if (this.value < ' . $this->params['min'] . ' OR this.value > ' . $this->params['max'] . ') {'.
            ' e.preventDefault(); this.focus(); alert(\'Value not find in range\'); }';
        return $js;
    }
}