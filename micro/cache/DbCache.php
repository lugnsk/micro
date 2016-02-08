<?php /** MicroDbCache */

namespace Micro\cache;

use Micro\Db\IConnection;
use Micro\Mvc\Models\Query;

/**
 * Class DbCache
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Cache
 * @version 1.0
 * @since 1.0
 */
class DbCache extends BaseCache
{
    /** @var IConnection $driver DB driver */
    protected $driver;
    /** @var string $table table name */
    protected $table;

    /**
     * Constructor
     *
     * @access public
     *
     * @param array $config config array
     *
     * @result void
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->table = 'cache';
        if (!empty($config['table'])) {
            $this->table = $config['table'];
            unset($config['table']);
        }

        $cls = $config['class'];
        $this->driver = new $cls($config);

        $this->driver->createTable($this->table, [
            '`name` VARCHAR(127) NOT NULL',
            '`value` TEXT NULL',
            '`duration` INT(11) NOT NULL',
            '`date_create` INT(11) NOT NULL',
            'UNIQUE(`name`)'
        ], '');
    }

    /**
     * @inheritdoc
     */
    public function check()
    {
        return ($this->driver instanceof IConnection) ?: false;
    }

    /**
     * @inheritdoc
     * @throws \Micro\Base\Exception
     */
    public function get($name)
    {
        /** @var array $element */
        if ($element = $this->getElement($name)) {
            return array_key_exists('value', $element) ? $element['value'] : null;
        }

        return null;
    }

    /**
     * @inheritdoc
     * @throws \Micro\Base\Exception
     */
    protected function getElement($name)
    {
        $query = new Query($this->driver);
        $query->table = $this->table;
        $query->addWhere('`name`=:name');
        $query->params = ['name' => $name];
        $query->limit = 1;
        $query->single = true;

        return $query->run(\PDO::FETCH_ASSOC);
    }

    /**
     * @inheritdoc
     */
    public function set($name, $value)
    {
        return $this->driver->update($this->table, ['`name`="' . $value . '"'], 'name="' . $name . '"');
    }

    /**
     * @inheritdoc
     */
    public function delete($name)
    {
        return $this->driver->delete($this->table, 'name=:name', ['name' => $name]);
    }

    /**
     * @inheritdoc
     */
    public function clean()
    {
        $this->driver->clearTable($this->table);
    }

    /**
     * @inheritdoc
     */
    public function info()
    {
        return $this->driver->count(null, $this->table);
    }

    /**
     * @inheritdoc
     * @throws \Micro\Base\Exception
     */
    public function getMeta($id)
    {
        return $this->getElement($id);
    }

    /**
     * @inheritdoc
     */
    public function increment($name, $offset = 1)
    {
        return $this->driver->update($this->table, ['value' => 'value+' . $offset], 'name="' . $name . '"');
    }

    /**
     * @inheritdoc
     */
    public function decrement($name, $offset = 1)
    {
        return $this->driver->update($this->table, ['value' => 'value-' . $offset], 'name="' . $name . '"');
    }
} 
