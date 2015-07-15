<?php /** MicroActionsGridColumn */

namespace Micro\widgets;

use Micro\base\Exception;
use Micro\wrappers\Html;

/**
 * Actions grid column class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage widgets
 * @version 1.0
 * @since 1.0
 */
class ActionsGridColumn extends GridColumn
{
    /** @var array $buttons buttons setup */
    public $buttons = [];


    /**
     * Constructor actions columns
     *
     * @access public
     *
     * @param array $params Setup actions columns
     *
     * @result void
     * @throws Exception
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);

        if (empty($this->params['link'])) {
            throw new Exception('Link for grid columns actions not defined!');
        }

        if (empty($this->params['template'])) {
            $this->params['template'] = '{view} {edit} {delete}';
        }

        $this->buttons = [
            'view' => [
                'link' => '/',
                'text' => 'view',
                'attributes' => []
            ],
            'edit' => [
                'link' => '/edit/',
                'text' => 'edit',
                'attributes' => []
            ],
            'delete' => [
                'link' => '/del/',
                'text' => 'delete',
                'attributes' => ['onclick' => 'return confirm(\'Are you sure?\')']
            ]
        ];

        $this->buttons = array_merge($this->buttons,
            (!empty($this->params['buttons']) ? $this->params['buttons'] : []));
    }

    /**
     * Convert object to string
     *
     * @access public
     *
     * @return string
     */
    public function __toString()
    {
        $result = [];

        foreach ($this->buttons AS $key => $row) {
            $result['{' . $key . '}'] = Html::href(
                !empty($row['text']) ? $row['text'] : $key,
                $this->params['link'] . (!empty($row['link']) ? $row['link'] : '/') . $this->params['pKey'],
                !empty($row['attributes']) ? $row['attributes'] : []
            );
        }

        return (string)str_replace(array_keys($result), array_values($result), $this->params['template']);
    }
}