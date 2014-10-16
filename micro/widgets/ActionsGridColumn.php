<?php /** MicroActionsGridColumn */

namespace Micro\widgets;

use Micro\wrappers\Html;

/**
 * Actions grid column class file.
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

        $viewLink = (isset($this->params['viewLink']) ? $this->params['viewLink'] : $this->params['link'] . '/');
        $r = Html::href(
            isset($this->params['viewText']) ? $this->params['viewText'] : 'view',
            $viewLink . $this->params['pKey']
        );

        $editLink = (isset($this->params['editLink']) ? $this->params['editLink'] : $this->params['link'] . '/edit/');
        $w = Html::href(
            isset($this->params['editText']) ? $this->params['editText'] : 'edit',
            $editLink . $this->params['pKey']
        );

        $deleteLink = (isset($this->params['deleteLink']) ? $this->params['deleteLink'] : $this->params['link'].'/del/');
        $d = Html::href(
            isset($this->params['deleteText']) ? $this->params['deleteText'] : 'delete',
            $deleteLink . $this->params['pKey'],
            ['onclick'=>'return confirm(\'Are you sure?\')']
        );

        return str_replace('{view}', $r, str_replace('{edit}', $w,
            str_replace('{delete}', $d, $this->params['template'])
        ));
    }
}