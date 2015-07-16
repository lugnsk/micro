<?php /** MicroFormWidget */

namespace Micro\widgets;

use Micro\mvc\Widget;
use Micro\web\Form;
use Micro\wrappers\Html;

/**
 * FormWidget class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage widget
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
    /** @var array $attributes attributes for form element */
    public $attributes = [];

    /**
     * Initialize widget
     *
     * @access public
     *
     * @return Form
     */
    public function init()
    {
        $this->attributes['type'] = $this->type;
        echo Html::beginForm($this->action, $this->method, $this->attributes);

        return new Form;
    }

    /**
     * Running widget
     *
     * @access public
     *
     * @return void
     */
    public function run()
    {
        $result = Html::endForm();
        if ($this->client) {
            $result .= Html::script($this->client);
        }
        echo $result;
    }
}
