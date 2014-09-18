<?php /** MicroActionsGridColumn */

namespace Micro\widgets;

use Micro\web\helpers\Html;

/**
 * Class ActionsGridColumn
 *
 * @package Micro
 * @subpackage web\widgets
 */
class ActionsGridColumn extends GridColumn
{
    /**
     * Convert object to string
     *
     * @access public
     * @return mixed
     */
    public function __toString()
    {
        if (!isset($this->params['link']) OR empty($this->params['link'])) {
            return 'Link for actions column not defined!';
        }
        if (!isset($this->params['template']) OR empty($this->params['template'])) {
            $this->params['template'] = '{view} {edit} {delete}';
        }

        $r = Html::href(
            'view',
            $this->params['link'].'/'.$this->params['key']
        );
        $w = Html::href(
            'edit',
            $this->params['link'].'/edit/'.$this->params['key']
        );
        $d = Html::href(
            'delete',
            $this->params['link'].'/delete/'.$this->params['key'],
            ['onclick'=>'Are you sure?']
        );

        return str_replace('view', $r, str_replace('{edit}', $w,
            str_replace('{delete}', $d, $this->params['template'])
        ));
    }
}