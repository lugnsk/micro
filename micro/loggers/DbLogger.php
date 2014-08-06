<?php

namespace Micro\loggers;

class DbLogger extends LogInterface
{
    /** @var \Micro\db\DbConnection $conn */
    private $connect;
    /** @var string $tableName */
    public $tableName;

    public function __construct($params=[])
    {
        parent::__construct($params);
        $this->getConnect();

        $this->tableName = (isset($params['table']) AND !empty($params['table'])) ? $params['table']: 'logs';

        if (!$this->connect->tableExists($this->tableName)) {
            $this->connect->createTable(
                $this->tableName,
                array(
                    '`id` INT AUTO_INCREMENT',
                    '`level` VARCHAR(20) NOT NULL',
                    '`message` TEXT NOT NULL',
                    '`date_create` INT NOT NULL',
                    'PRIMARY KEY(id)'
                ),
                'ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci'
            );
        }
    }
    public function getConnect()
    {
        $this->connect = \Micro\base\Registry::get('db');
    }
    public function sendMessage($level, $message)
    {
        $this->connect->conn->prepare(
            'INSERT INTO '.$this->tableName.' (`level`,`message`,`date_create`) VALUES (:level,:message,:date_create);'
        )->execute(array(
            'level'=>$level,
            'message'=>$message,
            'date_create'=>$_SERVER['REQUEST_TIME'],
        ));
    }
}