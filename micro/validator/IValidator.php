<?php /** MicroInterfaceValidator */

namespace Micro\Validator;

use Micro\Form\IFormModel;

/**
 * Interface IValidator
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Validator
 * @version 1.0
 * @since 1.0
 * @interface
 * @property array $elements
 * @property array $errors
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
     * @throws \Micro\Base\Exception
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
