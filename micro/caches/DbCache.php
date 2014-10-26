<?php

namespace Micro\caches;


use Micro\db\DbConnection;
use Micro\db\Query;

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

    public function check()
    {
        return ($this->driver instanceof DbConnection) ? TRUE : FALSE;
    }

    public function get($name)
    {
        return $this->getElement($name)['value'];
    }

    public function set($name, $value)
    {
        return $this->driver->update($this->table, ['`name`="'.$value.'"'], 'name="'.$name.'"');
    }

    public function delete($name)
    {
        return $this->driver->delete($this->table, 'name=:name', ['name'=>$name]);
    }

    public function clean()
    {
        $this->driver->clearTable($this->table);
    }

    public function info()
    {
    }

    public function getMeta($id)
    {
    }

    public function increment($name, $offset = 1)
    {
    }

    public function decrement($name, $offset = 1)
    {
    }

    protected function getElement($name)
    {
        $query = new Query;
        $query->table = $this->table;
        $query->select = '`value`';
        $query->addWhere('`name`=:name');
        $query->params = ['name'=>$name];
        $query->limit = 1;
        $query->single = true;
        return $query->run(\PDO::FETCH_ASSOC);
    }
} 