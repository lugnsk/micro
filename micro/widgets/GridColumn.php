<?php /** MicroGridColumn */

namespace Micro\widgets;

/**
 * GridColumn class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage widgets
 * @version 1.0
 * @since 1.0
 */
abstract class GridColumn
{
    /** @var array $params  */
    public $params = [];


    /**
     * @access public
     * @param $params
     * @result void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }
}