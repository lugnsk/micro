<?php /** MicroModel */

namespace Micro\mvc\models;

use Micro\base\Exception;
use Micro\base\IContainer;
use Micro\base\Type;
use Micro\form\FormModel;

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
abstract class Model extends FormModel implements IModel
{
    /** @var string $primaryKey Primary key on table */
    public static $primaryKey = 'id';
    /** @var boolean $_isNewRecord is new record? */
    protected $_isNewRecord = false;
    /** @var array $cacheRelations cached loads relations */
    protected $cacheRelations = [];


    /**
     * Constructor for model
     *
     * @access public
     *
     * @param IContainer $container
     * @param boolean $new is new model?
     *
     * @result void
     */
    public function __construct(IContainer $container, $new = true)
    {
        parent::__construct($container);

        $this->_isNewRecord = $new;
    }

    /**
     * Finder by primary key
     *
     * @access public
     * @param int|string $value unique value
     * @param IContainer $container
     * @return mixed
     * @static
     */
    public static function findByPk($value, IContainer $container)
    {
        return self::findByAttributes([self::$primaryKey => $value], true, $container);
    }

    /**
     * Find models by attributes
     *
     * @access public
     * @param array $attributes attributes and data for search
     * @param bool $single single or more
     * @param IContainer $container
     * @return mixed
     */
    public static function findByAttributes(array $attributes = [], $single = false, IContainer $container)
    {
        $query = new Query($container->db);
        foreach ($attributes AS $key => $val) {
            $query->addWhere($key . ' = :' . $key);
        }
        $query->params = $attributes;

        return self::finder($query, $single);
    }

    /**
     * Finder data in DB
     *
     * @access public
     *
     * @param IQuery $query query to search
     * @param boolean $single is single
     * @param IContainer $container
     *
     * @return mixed One or more data
     * @static
     */
    public static function finder(IQuery $query = null, $single = false, IContainer $container = null)
    {
        $query = ($query instanceof Query) ? $query : new Query($container->db);
        $query->table = static::tableName() . ' `m`';
        $query->objectName = get_called_class();
        $query->single = $single;

        return $query->run();
    }

    /**
     * Find by model attribute values
     *
     * @access public
     *
     * @param bool $single Is a single?
     *
     * @return mixed
     */
    public function find($single = false)
    {
        return self::findByAttributes(Type::getVars($this), $single, $this->container);
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
        foreach ($this->container->db->listFields(static::tableName()) AS $field) {
            $fields[] = $field['field'];
        }

        return $fields;
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
        /** @var array $relation */
        if ($relation = $this->relations()->get($name)) {
            if (empty($this->cacheRelations[$name])) {
                $sql = new Query($this->container->db);

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

                $sql->params[$relation['On'][0]] = $this->{$relation['On'][0]};

                /** @noinspection PhpUndefinedMethodInspection */
                $this->cacheRelations[$name] = $relation['Model']::finder($sql, !$relation['IsMany'], $this->container);
            }

            return $this->cacheRelations[$name];
        } elseif (isset($this->$name)) {
            return $this->$name;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function relations()
    {
        $keys = new Relations;

        // add any keys
        return $keys;
    }

    /**
     * Save changes
     *
     * @access public
     *
     * @param bool $validate Validated data?
     *
     * @return boolean
     * @throws Exception
     */
    final public function save($validate = false)
    {
        if ($validate && !$this->validate()) {
            return false;
        }

        if ($this->isNewRecord()) {
            return $this->create();
        } else {
            if ($this->beforeSave() && $this->update()) {
                $this->afterSave();

                return true;
            }
        }

        return false;
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
     * Create changes
     *
     * @access public
     * @return boolean
     * @throws Exception
     */
    final public function create()
    {
        if (!$this->isNewRecord()) {
            return false;
        }
        if ($this->beforeCreate() && $this->beforeSave()) {
            $id = $this->container->db->insert(static::tableName(), $this->mergeAttributesDb());
            if (!$id) {
                return false;
            }

            $pKey = self::$primaryKey ?: 'id';
            if ($this->checkAttributeExists($pKey)) {
                $this->$pKey = $id;
            }

            $this->_isNewRecord = false;

            $this->afterCreate();
            $this->afterSave();

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function beforeCreate()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave()
    {
        return true;
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
        foreach ($this->container->db->listFields(static::tableName()) AS $row) {
            $buffer[] = $row['field'];
        }

        foreach ($arr AS $key => $val) {
            if (!in_array($key, $buffer, true)) {
                unset($arr[$key]);
            }
        }

        unset($arr['isNewRecord']);

        return $arr;
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
        foreach ($this->container->db->listFields(static::tableName()) AS $row) {
            if ($row['field'] === $name) {
                $res = true;
                break;
            }
        }

        return $res;
    }

    /**
     * @inheritdoc
     */
    public function afterCreate()
    {
    }

    /**
     * @inheritdoc
     */
    public function afterSave()
    {
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
                if (self::$primaryKey) {
                    $where .= '`' . self::$primaryKey . '` = :' . self::$primaryKey;
                } else {
                    throw new Exception ($this->container,
                        'In table ' . static::tableName() . ' option `id` not defined/not use.'
                    );
                }
            }

            if ($this->container->db->update(static::tableName(), $this->mergeAttributesDb(), $where)) {
                $this->afterUpdate();

                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function beforeUpdate()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterUpdate()
    {
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
            if (!self::$primaryKey) {
                throw new Exception($this->container,
                    'In table ' . static::tableName() . ' option `id` not defined/not use.'
                );
            }

            if (
            $this->container->db->delete(
                static::tableName(),
                self::$primaryKey . '=:' . self::$primaryKey, [self::$primaryKey => $this->{self::$primaryKey}]
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
     * @inheritdoc
     */
    public function beforeDelete()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
    }
}
