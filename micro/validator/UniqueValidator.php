<?php /** MicroUniqueValidator */

namespace Micro\validators;

use Micro\base\Validator;
use Micro\db\Model;
use Micro\db\Query;

/**
 * UniqueValidator class file.
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
class UniqueValidator extends Validator
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
            $elementValue = $model->$element;

            $query = new Query($this->container);
            $query->select = $this->params['attribute'];
            $query->table = $this->params['table'];
            $query->addWhere($this->params['attribute'] . '="' . $elementValue . '"');
            $query->limit = 1;
            $query->single = true;

            if ($query->run()) {
                return false;
            }
        }

        return true;
    }
}