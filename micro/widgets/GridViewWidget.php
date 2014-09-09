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
    /** @var array $labels Labels for columns */
    public $labels;
    /** @var int $limit Limit current rows */
    public $limit = 10;
    /** @var int $page Current page on table */
    public $page = 1;
    /** @var array $paginationConfig parameters for PaginationWidget */
    public $paginationConfig = [];
    /** @var int $rowCount summary lines */
    protected $rowCount = 0;
    /** @var array Rows table */
    protected $rows = [];
    /** @var array Keys table */
    protected $keys = [];
    /** @var DbConnection current connection to db */
    protected $conn;


    /**
     * Redeclare widget constructor
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
        $table = [];

        $headerCells = [];
        foreach ($this->attributeLabels() AS $key) {
            $headerCells[] = ['value'=>$key];
        }
        $table[] = [ 'cells'=>$headerCells, 'header'=>true ];

        foreach ($this->rows AS $row) {
            $compileRow = [];
            foreach ($row AS $cell) {
                $compileRow[] = ['value'=>$cell];
            }
            $table[] = ['cells'=>$compileRow];
        }


        echo $this->render('gridview',[
            'rowCount'=>$this->rowCount,
            'table'=>$table,
            'attributes'=>['border'=>1, 'width'=>'100%'],
            'attributesCounter'=>['style'=>'text-align:right'],
            'counterText'=>'Всего: ',
            'paginationConfig'=>$this->paginationConfig
        ]);
    }

    /**
     * Returning labels for keys
     *
     * @access public
     * @return array
     */
    public function attributeLabels()
    {
        $result = [];

        foreach ($this->keys AS $num=>$key) {
            if (isset($this->labels[$key])) {
                $result[$num] = $this->labels[$key];
            } else {
                $result[$num] = $key;
            }
        }
        return $result;
    }
}