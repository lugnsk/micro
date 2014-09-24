<?php /** MicroDetailViewWidget */

namespace Micro\widgets;

use Micro\base\Exception;
use Micro\base\Registry;
use Micro\base\Widget;
use Micro\db\Model;
use Micro\db\Query;
use Micro\web\helpers\Html;

/**
 * DetailViewWidget class file.
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
class DetailViewWidget extends Widget
{
    /** @var Model $model Model for details */
    public $model = null;
    /** @var string $table Table for details */
    public $table='';
    /** @var string $condition Condition for generate with table */
    public $condition='';
    /** @var array $keys Keys for render */
    public $keys = [];
    /** @var array $attributes attributes for dl */
    public $attributes = [];
    /** @var array $attributesElement attributes for dt */
    public $attributesElement = [];
    /** @var array $attributesValue attributes for dd */
    public $attributesValue = [];
    /** @var array $attributeLabels labels for attributes */
    public $attributeLabels = [];

    /** @var \Micro\db\DbConnection $conn connect to database */
    protected $conn = null;
    /** @var array $statement elements from data */
    protected $statement = [];


    /**
     * Re-declare constructor class
     *
     * @access public
     * @param array $args arguments
     * @result void
     */
    public function __construct($args=[])
    {
        parent::__construct($args);
        $this->getConnect();
    }

    /**
     * Get connect to DB
     *
     * @access public
     * @return void
     */
    public function getConnect()
    {
        $this->conn = Registry::get('db');
    }

    /**
     * Initialize widget
     *
     * @access public
     * @result void
     */
    public function init()
    {
        if ($this->model instanceof Model) {
            $this->statement = \Micro\db\getVars($this->model);
            $cls = get_class($this->model);
            $this->table = $cls::tableName();
        } else {
            $query = new Query;
            $query->table = $this->table;
            $query->where = $this->condition;
            $query->limit = 1;
            $query->single = true;
            $this->statement = $query->run(\PDO::FETCH_ASSOC);
        }

        if (!$this->statement) {
            throw new Exception('Elements for render not found');
        }

        $fields = $this->conn->listFields($this->table);

        $fieldKeys = [];
        foreach($fields AS $field) {
            $fieldKeys[] = $field['field'];
        }

        if ($this->keys) {
            $this->keys = array_intersect($fieldKeys, $this->keys);
        } else {
            $this->keys = $fieldKeys;
        }
    }

    /**
     * Running widget
     *
     * @access public
     * @return void
     */
    public function run()
    {
        $result = Html::openTag('dl', $this->attributes);
        foreach ($this->statement AS $key=>$value) {
            if (in_array($key, $this->keys)) {
                $result .= Html::openTag('dt',$this->attributesElement);
                $result .= $this->getAttributeLabel($key);
                $result .= Html::closeTag('dt');
                $result .= Html::openTag('dd',$this->attributesValue);
                $result .= $value;
                $result .= Html::closeTag('dd');
            }
        }
        echo $result , Html::closeTag('dl');
    }

    /**
     * Get label for attribute
     *
     * @access public
     * @param string $key key of label search
     * @return string
     */
    public function getAttributeLabel($key)
    {
        if (array_key_exists($key, $this->attributeLabels)) {
            return $this->attributeLabels[$key];
        }
        return $key;
    }
}