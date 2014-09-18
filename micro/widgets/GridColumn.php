<?php /** MicroGridColumn */

namespace Micro\widgets;

/**
 * Class GridColumn
 * @package Micro\web\widgets
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