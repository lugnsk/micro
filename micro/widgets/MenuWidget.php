<?php /** MicroMenuWidget */

namespace Micro\widgets;

/**
 * MenuWidget class file.
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
class MenuWidget extends \Micro\base\Widget
{
    /** @property array $menu */
    public $menu = [];
    /** @property array $attributes */
    public $attributes = [];

    /**
     * Constructor for widget
     *
     * @access public
     * @param array $items
     * @param array $attributes
     * @result void
     */
    public function __construct($items = [], $attributes = [])
    {
        parent::__construct();

        $this->menu = $items;
        $this->attributes = $attributes;
    }

    /**
     * Running widget
     *
     * @access public
     * @return void
     */
    public function run()
    {
        echo \Micro\web\helpers\Html::lists($this->menu, $this->attributes);
    }

    /**
     * Initialize widget
     *
     * @access public
     * @return void
     */
    public function init()
    {
    }
}