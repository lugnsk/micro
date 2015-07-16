<?php /** MicroDbLogger */

namespace Micro\loggers;

use Micro\base\Container;

/**
 * DB logger class file.
 *
 * Writer logs in DB
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage logger
 * @version 1.0
 * @since 1.0
 */
class DbLogger extends LogInterface
{
    /** @var string $tableName logger table name */
    public $tableName;
    /** @var \Micro\db\DbConnection $conn connect to DB */
    protected $conn;

    /**
     * Constructor prepare DB
     *
     * @access public
     *
     * @param array $params configuration params
     *
     * @result void
     */
    public function __construct(Container $container, array $params = [])
    {
        parent::__construct($container, $params);

        $this->tableName = !empty($params['table']) ? $params['table'] : 'logs';

        if (!$this->container->db->tableExists($this->tableName)) {
            $this->container->db->createTable(
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
     * Send log message into DB
     *
     * @access public
     *
     * @param integer $level level number
     * @param string $message message to write
     *
     * @return void
     */
    public function sendMessage($level, $message)
    {
        $this->container->db->insert($this->tableName, [
            'level' => $level,
            'message' => $message,
            'date_create' => $_SERVER['REQUEST_TIME']
        ]);
    }
}