<?php /** MicroDbCache */

namespace Micro\caches;

use Micro\db\DbConnection;
use Micro\db\Query;

/**
 * Class DbCache
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage caches
 * @version 1.0
 * @since 1.0
 */
class DbCache implements Cache
{
    /** @var DbConnection $driver DB driver */
    protected $driver;
    /** @var string $table table name */
    protected $table;

    public function __construct($config)
    {
        $this->table = 'cache';
        if (isset($config['table'])) {
            $this->table = $config['table'];
            unset($config['table']);
        }

        $this->driver = new DbConnection($config);

        $this->driver->createTable($this->table, [
            '`name` VARCHAR(127) NOT NULL',
            '`value` TEXT NULL',
            '`duration` INT(11) NOT NULL',
            '`date_create` INT(11) NOT NULL',
            'UNIQUE(`name`)'
        ], '');
    }

    /**
     * Check driver
     *
     * @access public
     * @return mixed
     */
    public function check()
    {
        return ($this->driver instanceof DbConnection) ? TRUE : FALSE;
    }

    /**
     * Get value by name
     *
     * @access public
     * @param string $name key name
     * @return mixed
     */
    public function get($name)
    {
        return $this->getElement($name)['value'];
    }

    /**
     * Set value of element
     *
     * @access public
     * @param string $name key name
     * @param mixed $value value
     * @return mixed
     */
    public function set($name, $value)
    {
        return $this->driver->update($this->table, ['`name`="'.$value.'"'], 'name="'.$name.'"');
    }

    /**
     * Delete by key name
     *
     * @access public
     * @param string $name key name
     * @return mixed
     */
    public function delete($name)
    {
        return $this->driver->delete($this->table, 'name=:name', ['name'=>$name]);
    }

    /**
     * Clean all data from cache
     *
     * @access public
     * @return mixed
     */
    public function clean()
    {
        $this->driver->clearTable($this->table);
    }

    /**
     * Summary info about cache
     *
     * @access public
     * @return mixed
     */
    public function info()
    {
        return $this->driver->count(null, $this->table);
    }

    /**
     * Get meta-data of key id
     *
     * @access public
     * @param string $id key id
     * @return mixed
     */
    public function getMeta($id)
    {
        return $this->getElement($id);
    }

    public function increment($name, $offset = 1)
    {
        return $this->driver->update($this->table, ['value'=>'value+'.$offset], 'name="'.$name.'"');
    }

    public function decrement($name, $offset = 1)
    {
        return $this->driver->update($this->table, ['value'=>'value-'.$offset], 'name="'.$name.'"');
    }

    protected function getElement($name)
    {
        $query = new Query;
        $query->table = $this->table;
        $query->addWhere('`name`=:name');
        $query->params = ['name'=>$name];
        $query->limit = 1;
        $query->single = true;
        return $query->run(\PDO::FETCH_ASSOC);
    }
} 