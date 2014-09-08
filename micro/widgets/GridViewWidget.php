<?php

namespace Micro\widgets;

use Micro\base\Exception;
use Micro\base\Registry;
use Micro\base\Widget;
use Micro\db\DbConnection;

class GridViewWidget extends Widget
{
    /** @var string $query query for get lines */
    public $query;
    /** @var array $labels Labels for columns */
    public $labels;
    /** @var int $rowCount summary lines */
    protected $rowCount = 0;
    /** @var array Rows table */
    protected $rows = [];
    /** @var array Keys table */
    protected $keys = [];
    /** @var DbConnection current connection to db */
    protected $conn;


    public function __construct($args=[])
    {
        parent::__construct($args);

        if (!$this->query) {
            throw new Exception('Grid view initialize error, query not set or empty');
        }
        $this->getConnect();
    }

    public function getConnect()
    {
        $this->conn = Registry::get('db');
    }

    public function init()
    {
        $st = $this->conn->conn->query($this->query);
        $st->execute();

        $firstLine = $st->fetch(\PDO::FETCH_ASSOC);

        $this->keys = array_keys($firstLine);
        $this->rows = $st->fetchAll(\PDO::FETCH_ASSOC);
        array_unshift($this->rows, $firstLine);

        $this->rowCount = $this->conn->count($this->query);
    }
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
            'counterText'=>'Всего: '
        ]);
    }
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