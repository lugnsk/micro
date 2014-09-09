<?php

namespace Micro\widgets;

use Micro\base\Registry;
use Micro\base\Widget;
use Micro\db\Model;
use Micro\db\Query;

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
    /** @var array $attributeLabels labels for attributes */
    public $attributeLabels = [];

    /** @var \Micro\db\DbConnection $conn connect to database */
    protected $conn = null;
    protected $statement = [];


    /**
     * @param array $args arguments
     */
    public function __construct($args=[])
    {
        parent::__construct($args);
        $this->getConnect();
    }
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
        $result = '<dl>';
        foreach ($this->statement AS $key=>$value) {
            if (in_array($key, $this->keys)) {
                $result .= '<dt>'.$this->getAttributeLabel($key).'</dt><dd>'.$value.'</dd>';
            }
        }
        echo $result , '</dl>';
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