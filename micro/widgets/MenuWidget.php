<?php /** MicroMenuWidget */

namespace Micro\widgets;

use \Micro\web\helpers\Html;
use \Micro\base\Widget;

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
class MenuWidget extends Widget
{
    /** @var array $menu multiple menu array */
    public $menu = [];
    /** @var array $attributes attributes of menu */
    public $attributes = [];

    /**
     * Constructor for widget
     *
     * @access public
     * @param array $items menu items
     * @param array $attributes menu attributes
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
        echo Html::lists($this->menu, $this->attributes);
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