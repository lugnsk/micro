<?php /** MicroUniqueValidator */

namespace Micro\validator;

use Micro\form\IFormModel;
use Micro\mvc\models\Query;

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
class UniqueValidator extends BaseValidator
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
            $elementValue = $model->$element;

            $query = new Query($this->container->db);
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

    /**
     * @inheritdoc
     */
    public function client(IFormModel $model)
    {
        return '';
    }
}
