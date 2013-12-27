<?php /** MicroDetailViewWidget */
namespace Micro\widgets;

use Micro\db\Model;
use Micro\db\Query;
use Micro\mvc\Widget;
use Micro\wrappers\Html;
use Micro\base\Exception;

/**
 * DetailViewWidget class file.
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
    public function __construct( array $args = [] )
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
                        throw new Exception('Data query not set table or objectName');
                    }
                    $this->data = $args['data']->run();
                } elseif (is_subclass_of($args['data'], 'Micro\db\Model')) {
                    $this->data = $args['data'];
                } else {
                    throw new Exception('Argument "model" not supported type into DetailViewWidget');
                }
                $this->keys = $this->data->getAttributes();
                break;
            }
            default: {
                throw new Exception('Argument "model" not supported type into DetailViewWidget');
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
     */
    public function init()
    {
        foreach ($this->columns AS $key=>$val) {
            $arg = is_int($key) ? $val : $key;

            if ( !in_array($arg, $this->keys, true) ) {
                unset($this->columns[$key]);
                continue;
            }

            if ( !is_array( $val ) ) {
                $label = ucfirst( method_exists( $this->data, 'getLabel' ) ? $this->data->getLabel( $arg ) : $arg );

                $buffer = array(
                    'label' => $label,
                    'type'  => 'text',
                    'value' => $val
                );
                $this->columns[$key] = $buffer;
            } else {
                $buffer = array(
                    'label' => $val['label'] ?: ucfirst($arg),
                    'type'  => $val['type']  ?: 'text',
                    'value' => $val['value'] ?: $arg
                );

                $this->columns[$key] = $buffer;
            }
        }
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

        foreach ($this->columns AS $key=>$val) {
            $result .= Html::openTag('dt', $this->attributesElement);
            $result .= $val['label'];
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

        echo $result , Html::closeTag('dl');
    }
}