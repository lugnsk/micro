<?php /** MicroFormWidget */

namespace Micro\widgets;

use \Micro\web\helpers\Html;
use \Micro\web\Form;
use \Micro\base\Widget;

/**
 * FormWidget class file.
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
class FormWidget extends Widget
{
    /** @property string $action */
    public $action = '';
    /** @property string $method */
    public $method = 'GET';
    /** @property string $type */
    public $type = 'text/plain';

    /**
     * Initialize widget
     *
     * @access public
     * @return Form
     */
    public function init()
    {
        $this->action = ($this->action) ? $this->action : $_SERVER['REQUEST_URI'];
        echo Html::beginForm($this->action, $this->method, ['type' => $this->type]);
        return new Form;
    }

    /**
     * Running widget
     *
     * @access public
     * @return void
     */
    public function run()
    {
        echo Html::endForm();
    }
}