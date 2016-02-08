<?php /** FilterMicro */

namespace Micro\Filter;

use Micro\Base\IContainer;

/**
 * Filter class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Filter
 * @version 1.0
 * @since 1.0
 */
abstract class Filter implements IFilter
{
    /** @var array|string|bool $result Result array */
    public $result;
    /** @var IContainer $Container */
    protected $container;
    /** @var string $action Current action */
    protected $action;

    /**
     * @param string $action current action
     * @param IContainer $container
     */
    public function __construct($action, IContainer $container)
    {
        $this->action = $action;
        $this->container = $container;
    }
}
