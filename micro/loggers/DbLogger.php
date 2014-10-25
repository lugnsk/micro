<?php /** MicroDbLogger */

namespace Micro\loggers;

use Micro\base\Registry;

/**
 * DB logger class file.
 *
 * Writer logs in DB
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage loggers
 * @version 1.0
 * @since 1.0
 */
class DbLogger extends LogInterface
{
    /** @var \Micro\db\DbConnection $connect connect to DB */
    protected $connect;
    /** @var string $tableName logger table name */
    public $tableName;

    /**
     * Constructor prepare DB
     *
     * @access public
     * @param array $params configuration params
     * @result void
     */
    public function __construct($params = [])
    {
        parent::__construct($params);
        $this->getConnect();

        $this->tableName = (isset($params['table']) AND !empty($params['table'])) ? $params['table'] : 'logs';

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

    /**
     * Get connect to database
     *
     * @access public
     * @return void
     */
    public function getConnect()
    {
        $this->connect = Registry::get('db');
    }

    /**
     * Send log message into DB
     *
     * @access public
     * @param integer $level level number
     * @param string $message message to write
     * @return void
     */
    public function sendMessage($level, $message)
    {
        $this->connect->insert($this->tableName, [
            'level' => $level,
            'message' => $message,
            'date_create' => $_SERVER['REQUEST_TIME'],
        ]);
    }
}