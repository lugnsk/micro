<?php /** MicroDetailViewWidget */
namespace Micro\widgets;

use Micro\base\Exception;
use Micro\db\Model;
use Micro\db\Query;
use Micro\mvc\Widget;
use Micro\wrappers\Html;

/**
 * DetailViewWidget class file.
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
class DetailViewWidget extends Widget
{
    /** @var array $columns Rendered columns */
    public $columns;
    /** @var array $attributes attributes for dl */
    public $attributes = [];
    /** @var array $attributesElement attributes for dt */
    public $attributesElement = [];
    /** @var array $attributesValue attributes for dd */
    public $attributesValue = [];
    /** @var array $attributeLabels labels for attributes */
    public $attributeLabels = [];

    /** @var array $keys Data keys */
    protected $keys;
    /** @var mixed $data Data source */
    protected $data;

    /**
     * Redeclare constructor, generate keys and data
     *
     * @access public
     *
     * @param array $args Arguments
     *
     * @result void
     * @throws \Micro\base\Exception
     */
    public function __construct(array $args = [])
    {
        parent::__construct($args);

        if (empty($args['data'])) {
            throw new Exception('Argument "data" not initialized into DetailViewWidget');
        }

        switch (gettype($args['data'])) {
            case 'array': {
                $this->data = (object)$args['data'];
                $this->keys = array_keys($args['data']);
                break;
            }
            case 'object': {
                if ($args['data'] instanceof Query) {
                    if ($args['data']->objectName) {
                        $cls = $args['data']->objectName;
                        $args['data']->table = $cls::tableName();
                    } elseif (!$args['data']->table) {
                        throw new Exception($this->container, 'Data query not set table or objectName');
                    }
                    $this->data = $args['data']->run();
                } elseif (is_subclass_of($args['data'], 'Micro\db\Model')) {
                    $this->data = $args['data'];
                } else {
                    throw new Exception($this->container, 'Argument "model" not supported type into DetailViewWidget');
                }
                $this->keys = $this->data->getAttributes();
                break;
            }
            default: {
                throw new Exception($this->container, 'Argument "model" not supported type into DetailViewWidget');
            }
        }
        if (empty($args['columns'])) {
            $this->columns = $this->keys;
        }
    }

    /**
     * Prepare selected rows
     *
     * @access public
     *
     * @return void
     * @throws Exception
     */
    public function init()
    {
        $result = [];

        // обходим заданные параметры
        foreach ($this->columns AS $key => $val) {
            $column = '';
            $data = [];

            // если параметр число и вал строка - это ключ
            if (is_int($key) && is_string($val)) {
                $column = $val;
                // если параметр строка и вал массив - is good
            } elseif (is_string($key) && is_array($val)) {
                $column = $key;
            } else {
                throw new Exception($this->container, 'Unknown `data` format into DetailViewWidget');
            }

            $result[] = [
                'title' => !empty($data['title']) ? $data['title'] : ucfirst(method_exists($this->data,
                    'getLabel') ? $this->data->getLabel($column) : $column),
                'type' => !empty($data['type']) ? $data['type'] : 'text',
                'value' => !empty($data['value']) ? $data['value'] : $this->data->{$column}
            ];
        }

        $this->columns = $result;
    }

    /**
     * Run drawing
     *
     * @access public
     *
     * @return void
     */
    public function run()
    {
        $result = Html::openTag('dl', $this->attributes);

        foreach ($this->columns AS $key => $val) {
            $result .= Html::openTag('dt', $this->attributesElement);
            $result .= $val['title'];
            $result .= Html::closeTag('dt');
            $result .= Html::openTag('dd', $this->attributesValue);

            $buffer = '';
            switch ($val['type']) {
                case 'raw': {
                    $data = $this->data; // for eval
                    $buffer .= eval('return ' . $val['value']);
                    break;
                }
                default: {
                    if (property_exists($this->data, $val['value'])) {
                        $buffer .= htmlspecialchars($this->data->{$val['value']});
                    } else {
                        $buffer .= htmlspecialchars($val['value']);
                    }
                }
            }

            $result .= (strlen($buffer) ? $buffer : '&nbsp;') . Html::closeTag('dd');
        }

        echo $result, Html::closeTag('dl');
    }
}