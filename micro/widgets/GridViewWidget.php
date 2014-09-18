<?php /** MicroGridViewWidget */

namespace Micro\widgets;

use Micro\base\Exception;
use Micro\base\Registry;
use Micro\base\Widget;
use Micro\db\DbConnection;

/**
 * GridViewWidget class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage widgets
 * @version 1.0
 * @since 1.0
 */
class GridViewWidget extends Widget
{
    /** @var string $query query for get lines */
    public $query;
    /** @var int $limit Limit current rows */
    public $limit = 10;
    /** @var int $page Current page on table */
    public $page = 1;
    /** @var array $paginationConfig parameters for PaginationWidget */
    public $paginationConfig = [];
    /** @var array $tableConfig table configuration */
    public $tableConfig  = [];
    /** @var array $attributes attributes for table */
    public $attributes = [];
    /** @var array $attributesCounter attributes for counter */
    public $attributesCounter = [];
    /** @var string $textCounter text for before counter */
    public $textCounter = 'Всего: ';

    /** @var int $rowCount summary lines */
    protected $rowCount = 0;
    /** @var array Rows table */
    protected $rows = [];
    /** @var array Keys table */
    protected $keys = [];
    /** @var DbConnection current connection to db */
    protected $conn;


    /**
     * Re-declare widget constructor
     *
     * @access public
     * @param array $args arguments
     * @result void
     * @throws Exception
     */
    public function __construct($args=[])
    {
        parent::__construct($args);

        if (!$this->query) {
            throw new Exception('Grid view initialize error, query not set or empty');
        }
        $this->getConnect();
    }

    /**
     * Get connect to DB
     *
     * @access public
     * @return void
     */
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
        if (($position = strpos($this->query, 'LIMIT')) !== FALSE) {
            $this->query = substr($this->query, 0, $position);
        }

        $st = $this->conn->conn->query($this->query);
        $st->execute();
        $this->keys = array_keys($st->fetch(\PDO::FETCH_ASSOC));
        $this->rowCount = $this->conn->count($this->query);

        $st = $this->conn->conn->query($this->query.' LIMIT '.($this->limit*$this->page).','.$this->limit);
        $st->execute();
        $this->rows = $st->fetchAll(\PDO::FETCH_ASSOC);

        if ($this->limit < 10) {
            $this->limit = 10;
        }

        /**
         * Table configuration
         *
         * Basic example:
         * <?php
         *  $this->tableConfig = [
         *      'name' => [
         *          'header'=>'',
         *          'filter'=>'',
         *          'type'=>'',
         *          'value'=>''
         *      ],
         * ];
         * ?>
         */
        if (!$this->tableConfig) {
            foreach ($this->keys AS $key) {
                $this->tableConfig[$key] = [
                    'header'=>$key,
                    'type'=>'string',
                    'value'=>null,
                    'class'=>null,
                ];
            }
        }

        $this->paginationConfig['countRows'] = $this->rowCount;
        $this->paginationConfig['limit'] = $this->limit;
        $this->paginationConfig['currentPage'] = $this->page;
    }

    /**
     * Running widget
     *
     * @access public
     * @return void
     */
    public function run()
    {
        echo $this->render('gridview',[
            'keys'=>$this->keys,
            'rows'=>$this->rows,
            'rowCount'=>$this->rowCount,
            'paginationConfig'=>$this->paginationConfig,
            'tableConfig'=>$this->tableConfig,
            'attributes'=>$this->attributes,
            'attributesCounter'=>$this->attributesCounter,
            'textCounter'=>$this->textCounter
        ]);
    }
}