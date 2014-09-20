<?php /** MicroGridViewWidget */

namespace Micro\widgets;

use Micro\base\Exception;
use Micro\base\Registry;
use Micro\base\Widget;
use Micro\db\DbConnection;
use Micro\web\helpers\Html;

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
    /** @var bool $filters rendered filters */
    public $filters = false;

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

        $this->makeTableConfig();

        $this->paginationConfig['countRows'] = $this->rowCount;
        $this->paginationConfig['limit'] = $this->limit;
        $this->paginationConfig['currentPage'] = $this->page;
    }

    /**
     * Make table configuration
     *
     * @access public
     * @return void
     */
    private function makeTableConfig()
    {
        if (!$this->tableConfig) {
            foreach ($this->keys AS $key) {
                $this->tableConfig[$key] = [
                    'header'=>$key,
                    'filter'=>'<input type="text" name="'.$key.'" value="" />',
                    'value'=>null,
                    'class'=>null,
                ];
            }
        }

        foreach ($this->tableConfig AS $conf) {
            if (isset($conf['filter'])) {
                $this->filters = true;
                break;
            }
        }
    }

    /**
     * Running widget
     *
     * @access public
     * @return void
     */
    public function run()
    {
        echo $this->renderCounter();
        echo Html::openTag('table', $this->attributes);
        echo $this->renderHeading();
        echo $this->renderFilters();
        echo $this->renderRows();
        echo Html::closeTag('table');
        echo $this->widget('Micro\widgets\PaginationWidget',$this->paginationConfig);
    }

    /**
     * Render counter
     *
     * @access private
     * @return string
     */
    private function renderCounter()
    {
        $result  = Html::openTag('div', $this->attributesCounter);
        $result .= $this->textCounter . $this->rowCount;
        $result .= Html::closeTag('div');
        return $result;
    }

    /**
     * Render heading rows
     *
     * @access private
     * @return string
     */
    private function renderHeading()
    {
        $result = Html::openTag('tr');
        foreach ($this->tableConfig AS $key=>$row) {
            $result .= Html::openTag('th');
            $result .= isset($row['header']) ? $row['header'] : $key;
            $result .= Html::closeTag('th');
        }
        $result .= Html::closeTag('tr');
        return $result;
    }

    /**
     * Render filters
     *
     * @access private
     * @return string
     */
    private function renderFilters()
    {
        $result = null;
        if ($this->filters) {
            $result .= Html::openTag('tr');
            foreach ($this->tableConfig AS $key=>$row) {
                $result .= Html::openTag('td');
                $result .= isset($row['filter']) ? $row['filter'] : null;
                $result .= Html::closeTag('td');
            }
            $result .= Html::closeTag('tr');
        }
        return $result;
    }


    /**
     * Render rows
     *
     * @access private
     * @return string
     */
    private function renderRows()
    {
        $result = null;
        foreach ($this->rows AS $elem) {
            $result .= Html::openTag('tr');
            foreach ($this->tableConfig AS $key=>$row) {
                $result .= Html::openTag('td');
                if (isset($row['class']) AND is_subclass_of($row['class'], 'Micro\widgets\GridColumn')) {
                    $result .= new $row['class']($row + ['str'=>isset($elem) ? $elem : null , 'key'=>$elem['id']]);
                } elseif (isset($row['value'])) {
                    $result .= eval('return "'.$row['value'].'";');
                } else {
                    $result .= isset($elem[$key]) ? $elem[$key] : null;
                }
                $result .= Html::closeTag('td');
            }
            $result .= Html::closeTag('tr');
        }
        return $result;
    }
}