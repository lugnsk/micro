<?php /** MicroFormWidget */

namespace Micro\widgets;

use \Micro\wrappers\Html;
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
    /** @var string $action action url */
    public $action = '';
    /** @var string $method send form method */
    public $method = 'GET';
    /** @var string $type type of form */
    public $type = 'text/plain';
    /** @var string $client client js code */
    public $client = '';

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
        if ($this->client) {
            echo Html::script($this->client);
        }
    }
}