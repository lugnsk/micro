<?php /** MicroRangeValidator */

namespace Micro\validator;

use Micro\form\IFormModel;

/**
 * RangeValidator class file.
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
class RangeValidator extends BaseValidator
{
    /**
     * @inheritdoc
     */
    public function validate(IFormModel $model)
    {
        if (empty($this->params['min'])) {
            $this->errors[] = 'Minimal value not declared to Range validator';
        }
        if (empty($this->params['max'])) {
            $this->errors[] = 'Maximal value not declared to Range validator';
        }
        $step = (!empty($this->params['step'])) ? $this->params['step'] : 1;

        $rang = range($this->params['min'], $this->params['max'], $step);

        foreach ($this->elements AS $element) {
            if (!$model->checkAttributeExists($element)) {
                $this->errors[] = 'Parameter ' . $element . ' not defined in class ' . get_class($model);

                return false;
            }
            if (!in_array($model->$element, $rang, true)) {
                $this->errors[] = 'Parameter ' . $element . ' not find in rage ' .
                    $this->params['min'] . '..' . $this->params['max'];

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
        $js = 'if (this.value < ' . $this->params['min'] . ' OR this.value > ' . $this->params['max'] . ') {' .
            ' e.preventDefault(); this.focus(); alert(\'Value not find in range\'); }';

        return $js;
    }
}
