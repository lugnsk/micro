<?php /** MicroBaseValidator */

namespace Micro\Validator;

use Micro\Base\IContainer;

/**
 * Class BaseValidator
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Validator
 * @version 1.0
 * @since 1.0
 * @abstract
 */
abstract class BaseValidator extends \stdClass implements IValidator
{
    /** @var array $params */
    public $params = [];
    /** @var string[] $elements */
    public $elements = [];
    /** @var array $errors */
    public $errors = [];
    /** @var IContainer $container */
    protected $container;

    public function __construct(array $params = [])
    {
        foreach ($params AS $key => $val) {
            $this->$key = $val;
        }
    }
}
