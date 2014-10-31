<?php /** MicroModel */

namespace Micro\db;

use \Micro\base\Registry;
use \Micro\web\FormModel;
use \Micro\base\Exception;

/**
 * Get public vars into object
 *
 * @access public
 * @param mixed $object
 * @return array
 */
function getVars($object)
{
    return get_object_vars($object);
}

/**
 * Model class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage db
 * @version 1.0
 * @since 1.0
 */
abstract class Model extends FormModel
{
    /** @var \PDO $db pdo connection */
    protected $db = false;
    /** @var boolean $_isNewRecord is new record? */
    protected $_isNewRecord = false;
    /** @var array $cacheRelations cached loads relations */
    protected $cacheRelations = [];


    /**
     * Constructor for model
     *
     * @access public
     * @param boolean $new is new model?
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
        $this->db = Registry::get('db')->conn;
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
     * Finder data in DB
     *
     * @access public
     * @param Query $query query to search
     * @param boolean $single is single
     * @return mixed One or more data
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
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($relation = $this->relations()->get($name)) {
            if (!isset($this->cacheRelations[$name])) {
                $sql = new Query;
                $sql->addWhere('`m`.`' . $relation['On'][1] . '`="' . $this->{$relation['On'][0]} . '"');

                if ($relation['Where']) {
                    $sql->addWhere($relation['Where']);
                }
                if ($relation['Params']) {
                    $sql->params = $relation['Params'];
                }

                $this->cacheRelations[$name] = $relation['Model']::finder($sql, $relation['IsMany']);
            }
            return $this->cacheRelations[$name];
        }
        return $this->$name;
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
    public function create()
    {
        if ($this->beforeCreate()) {
            $arr = getVars($this);
            unset($arr['isNewRecord']);
            $arr_h = array_keys($arr);
            $typs = implode(',', $arr_h);
            $keys = ':' . implode(', :', $arr_h);

            $sth = $this->db->prepare(
                'INSERT INTO ' . $this->tableName() . ' (' . $typs . ') VALUES (' . $keys . ');'
            );

            if ($sth->execute($arr)) {
                $this->_isNewRecord = false;
                $this->afterCreate();
                return true;
            }
        }
        return false;
    }

    /**
     * After create actions
     *
     * @access public
     * @global Registry
     * @return void
     */
    public function afterCreate()
    {
        $pKey = isset($this->primaryKey) ? $this->primaryKey : 'id';

        if (property_exists($this, $pKey)) {
            $this->$pKey = $this->db->lastInsertId($pKey);
        }
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
    public function save()
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
     * @param string $where condition for search
     * @throws Exception
     * @return boolean
     */
    public function update($where = null)
    {
        if (!$this->isNewRecord()) {
            if ($this->beforeUpdate()) {
                $arr = getVars($this);
                unset($arr['isNewRecord']);

                $params = [];
                foreach ($arr AS $key => $val) {
                    if ($key == 'id') {
                        continue;
                    }
                    $params[] = $key . ' = :' . $key;
                }

                $query = 'UPDATE ' . $this->tableName() . ' SET ' . implode(', ', $params);
                if ($where) {
                    $query .= ' WHERE ' . $where;
                } elseif (isset($this->id) AND !empty($this->id)) {
                    $query .= ' WHERE id = :id';
                } else {
                    throw new Exception ('In table ' . $this->tableName() . ' option `id` not defined/not use.');
                }
                $sth = $this->db->prepare($query);

                if ($sth->execute($arr)) {
                    $this->afterUpdate();
                    return true;
                }
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
     */
    public function delete()
    {
        if (!$this->isNewRecord()) {
            if ($this->beforeDelete()) {

                $sth = $this->db->prepare(
                    'DELETE FROM ' . $this->tableName() . ' WHERE id=' . $this->id . ' LIMIT 1;'
                );

                if ($sth->execute()) {
                    $this->afterDelete();
                    unset($this);
                    return true;
                }
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
}