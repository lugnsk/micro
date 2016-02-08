<?php /** MicroGridColumn */

namespace Micro\Widget;

/**
 * Abstract grid column class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Widget
 * @version 1.0
 * @since 1.0
 * @abstract
 */
abstract class GridColumn
{
    /** @var array $params configuration array */
    public $params = [];


    /**
     * Constructor
     *
     * @access public
     *
     * @param array $params grid column configuration
     *
     * @result void
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }
}
