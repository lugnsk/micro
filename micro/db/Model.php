<?php /** MicroModel */

namespace Micro\db;

use Micro\base\Type;
use Micro\base\Exception;
use Micro\base\Registry;
use Micro\web\FormModel;

/**
 * Model class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage db
 * @version 1.0
 * @since 1.0
 */
abstract class Model extends FormModel
{
    /** @var DbConnection $db pdo connection */
    protected $db = false;
    /** @var boolean $_isNewRecord is new record? */
    protected $_isNewRecord = false;
    /** @var string $primaryKey Primary key on table */
    protected $primaryKey = 'id';
    /** @var array $cacheRelations cached loads relations */
    protected $cacheRelations = [];


    /**
     * Constructor for model
     *
     * @access public
     *
     * @param boolean $new is new model?
     *
     * @result void
     */
    public function __construct($new = true)
    {
        $this->_isNewRecord = $new;
        $this->getDbConnection();
    }

    /**
     * Get connection to db
     *
     * @access public
     * @global Registry
     * @return void
     */
    public function getDbConnection()
    {
        $this->db = Registry::get('db');
    }

    /**
     * Is new record?
     *
     * @access public
     * @return boolean
     */
    public function isNewRecord()
    {
        return $this->_isNewRecord;
    }

    /**
     * @access public
     *
     * @return string
     * @throws Exception
     * @static
     */
    public static function tableName()
    {
        throw new Exception('Function `tableName` not defined into ' . get_called_class());
    }

    /**
     * Finder data in DB
     *
     * @access public
     *
     * @param Query $query query to search
     * @param boolean $single is single
     *
     * @return mixed One or more data
     * @static
     */
    public static function finder($query = null, $single = false)
    {
        $query = ($query instanceof Query) ? $query : new Query;
        $query->table = static::tableName() . ' `m`';
        $query->objectName = get_called_class();
        $query->single = $single;
        return $query->run();
    }

    /**
     * Get attributes defined into model
     *
     * @access public
     *
     * @return array
     */
    public function getAttributes()
    {
        $fields = [];
        foreach (Registry::get('db')->listFields(static::tableName()) AS $field) {
            $fields[] = $field['field'];
        }
        return $fields;
    }

    /**
     * Relations for model
     *
     * @access public
     * @return Relations
     * ]
     */
    public function relations()
    {
        $keys = new Relations;
        // add any keys
        return $keys;
    }

    /**
     * Get relation data or magic properties
     *
     * @access public
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if ($relation = $this->relations()->get($name)) {
            if (empty($this->cacheRelations[$name])) {
                $sql = new Query;

                $sql->addWhere('`m`.`' . $relation['On'][1] . '`=:' . $relation['On'][0]);

                if ($relation['Where']) {
                    $sql->addWhere($relation['Where']);
                }
                if ($relation['Params']) {
                    $sql->params = $relation['Params'];
                }
                if ($relation['Limit'] > 0) {
                    $sql->limit = $relation['Limit'];
                }

                $sql->params[ $relation['On'][0] ] = $this->{$relation['On'][0]};

                /** @var Model $relation['Model'] */
                $this->cacheRelations[$name] = $relation['Model']::finder($sql, !$relation['IsMany']);
            }
            return $this->cacheRelations[$name];
        } elseif (isset($this->$name)) {
            return $this->$name;
        }
        return false;
    }

    /**
     * Before create actions
     *
     * @access public
     * @return boolean
     */
    public function beforeCreate()
    {
        return true;
    }

    /**
     * Create changes
     *
     * @access public
     * @return boolean
     */
    final public function create()
    {
        if (!$this->isNewRecord()) {
            return false;
        }
        if ($this->beforeCreate()) {
            $id = $this->db->insert(static::tableName(), $this->mergeAttributesDb());
            if (!$id) {
                return false;
            }

            $pKey = $this->primaryKey?:'id';
            if ($this->checkAttributeExists( $pKey )) {
                $this->$pKey = $id;
            }

            $this->_isNewRecord = false;
            $this->afterCreate();

            return true;
        }
        return false;
    }

    /**
     * After create actions
     *
     * @access public
     *
     * @return void
     */
    public function afterCreate()
    {
    }

    /**
     * Before save actions
     *
     * @access public
     * @return boolean
     */
    public function beforeSave()
    {
        return true;
    }

    /**
     * Save changes
     *
     * @access public
     * @return boolean
     */
    final public function save()
    {
        if ($this->isNewRecord()) {
            return $this->create();
        } else {
            if ($this->beforeSave()) {
                if ($this->update()) {
                    $this->afterSave();
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * After save actions
     *
     * @access public
     * @return void
     */
    public function afterSave()
    {
    }

    /**
     * Before update actions
     *
     * @access public
     * @return boolean
     */
    public function beforeUpdate()
    {
        return true;
    }

    /**
     * Update changes
     *
     * @access public
     *
     * @param string $where condition for search
     *
     * @throws Exception
     * @return boolean
     */
    final public function update($where = null)
    {
        if ($this->isNewRecord()) {
            return false;
        }
        if ($this->beforeUpdate()) {
            if (!$where) {
                if ($this->primaryKey) {
                    $where .= '`' . $this->primaryKey . '` = :' . $this->primaryKey;
                } else {
                    throw new Exception ('In table ' . static::tableName() . ' option `id` not defined/not use.');
                }
            }

            if ($this->db->update(static::tableName(), $this->mergeAttributesDb(), $where)) {
                $this->afterUpdate();
                return true;
            }
        }
        return false;
    }

    /**
     * After update actions
     *
     * @access public
     * @return boolean
     */
    public function afterUpdate()
    {
    }

    /**
     * Before delete actions
     *
     * @access public
     * @return boolean
     */
    public function beforeDelete()
    {
        return true;
    }

    /**
     * Delete changes
     *
     * @access public
     * @return boolean
     * @throws Exception
     */
    final public function delete()
    {
        if ($this->isNewRecord()) {
            return false;
        }
        if ($this->beforeDelete()) {
            if (!$this->primaryKey) {
                throw new Exception('In table ' . static::tableName() . ' option `id` not defined/not use.');
            }

            if (
            $this->db->delete(
                static::tableName(),
                $this->primaryKey . '=:' . $this->primaryKey, [$this->primaryKey => $this->{$this->primaryKey}]
            )
            ) {
                $this->afterDelete();
                unset($this);
                return true;
            }
        }
        return false;
    }

    /**
     * After delete actions
     *
     * @access public
     * @return void
     */
    public function afterDelete()
    {
    }

    /**
     * Check attribute exists into table
     *
     * @access public
     *
     * @param string $name Attribute name
     *
     * @return array
     */
    public function checkAttributeExists($name)
    {
        if (isset($this->$name)) {
            return true;
        }

        $res = false;
        foreach ($this->db->listFields(static::tableName()) AS $row) {
            if ($row['field'] === $name) {
                $res = true;
                break;
            }
        }

        return $res;
    }

    /**
     * Merge local attributes and db attributes
     *
     * @access protected
     *
     * @return array
     * @throws \Micro\base\Exception
     */
    protected function mergeAttributesDb()
    {
        $arr = Type::getVars($this);

        $buffer = [];
        foreach ($this->db->listFields(static::tableName()) AS $row) {
            $buffer[] = $row['field'];
        }

        foreach ($arr AS $key=>$val) {
            if (!in_array($key, $buffer)) {
                unset($arr[$key]);
            }
        }

        unset($arr['isNewRecord']);
        return $arr;
    }
}