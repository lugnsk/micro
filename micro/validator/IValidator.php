<?php /** MicroInterfaceValidator */

namespace Micro\validator;

use Micro\form\IFormModel;

/**
 * Interface IValidator
 *
 * @package Micro\validator
 *
 * @property array $errors
 * @property array $elements
 */
interface IValidator
{
    /**
     * Validate on server, make rule
     *
     * @access public
     *
     * @param IFormModel $model checked model
     *
     * @return bool
     */
    public function validate(IFormModel $model);

    /**
     * Client-side validation, make js rule
     *
     * @access public
     *
     * @param IFormModel $model model from elements
     *
     * @return string
     */
    public function client(IFormModel $model);
}
