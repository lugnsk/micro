<?php /** MicroGridViewWidget */

namespace Micro\widgets;

use Micro\base\Registry;
use Micro\mvc\Widget;
use Micro\db\DbConnection;
use Micro\wrappers\Html;

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
    public $tableConfig = [];
    /** @var array $attributes attributes for table */
    public $attributes = [];
    /** @var array $attributesCounter attributes for counter */
    public $attributesCounter = [];
    /** @var array $attributesHeading attributes for heading */
    public $attributesHeading = [];
    /** @var string $textCounter text for before counter */
    public $textCounter = 'Всего: ';
    /** @var bool $filters rendered filters */
    public $filters = false;
    /** @var array $rows Rows table */
    public $rows = [];
    /** @var array $keys Keys table */
    public $keys = [];
    /** @var string $emptyText text to render if rows not found */
    public $emptyText = 'Elements not found!';
    /** @var string $template Template render */
    public $template = '{counter}{data}{paginator}';

    /** @var int $rowCount summary lines */
    protected $rowCount = 0;
    /** @var DbConnection $conn current connection to db */
    protected $conn;


    /**
     * Re-declare widget constructor
     *
     * @access public
     * @param array $args arguments
     * @result void
     */
    public function __construct(array $args = [])
    {
        parent::__construct($args);
        $this->getConnect();
    }

    /**
     * Get connect to DB
     *
     * @access public
     * @global Registry
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
        if ($this->limit < 1) {
            $this->limit = 1;
        }

        if ($this->rows) {
            if (is_object($this->rows[0]) == true) {
                foreach ( $this->rows AS $key=>$row) {
                    $this->rows[$key] = (array)$row;
                    foreach($this->rows[$key] AS $num=>$val) {
                        if (is_array($this->rows[$key][$num]) OR is_object($this->rows[$key][$num])) {
                            unset($this->rows[$key][$num]);
                        }
                        if (strpos($num, 'isNewRecord') !== FALSE) {
                            unset($this->rows[$key][$num]);
                        }
                    }
                }
            }

            if ($this->query) {
                $this->query = null;
            }

            $this->rowCount = count($this->rows);
            $this->keys = array_keys($this->rows[0]);

            if ($this->rowCount > $this->limit) {
                $this->rows = array_slice($this->rows, ($this->page * $this->limit), $this->limit);
            }
        } else {
            if ($this->query) {
                $this->makeRows();
            }
        }

        $this->makeTableConfig();

        $this->paginationConfig['countRows'] = $this->rowCount;
        $this->paginationConfig['limit'] = $this->limit;
        $this->paginationConfig['currentPage'] = $this->page;
    }

    /**
     * Make rows from sql
     *
     * @access private
     * @return void
     */
    private function makeRows()
    {
        if (($position = strpos($this->query, 'LIMIT')) !== false) {
            $this->query = substr($this->query, 0, $position);
        }

        $st = $this->conn->rawQuery($this->query);
        if (!$st->rowCount()) {
            return;
        }
        $this->keys = array_keys($st->fetch(\PDO::FETCH_ASSOC));
        $this->rowCount = $this->conn->count($this->query);

        $st = $this->conn->rawQuery($this->query . ' LIMIT ' . ($this->page * $this->limit) . ',' . $this->limit);
        $this->rows = $st->fetchAll(\PDO::FETCH_ASSOC);
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
                    'header' => $key,
                    'filter' => '<input type="text" name="' . $key . '" value="" />',
                    'value' => null,
                    'class' => null,
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
        ob_start();
        $pager = new PaginationWidget($this->paginationConfig);
        $pager->init();
        $pager->run();
        $pager = ob_get_clean();

        $result = $this->template;
        $result = str_replace('{counter}', $this->renderCounter(), $result);
        $result = str_replace(
            '{paginator}',
            $pager,
            $result
        );


        $table = Html::openTag('table', $this->attributes);
        $table .= $this->renderHeading();
        $table .= $this->renderFilters();
        $table .= $this->renderRows();
        $table .= Html::closeTag('table');

        echo str_replace('{data}', $table, $result);
    }

    /**
     * Render counter
     *
     * @access private
     * @return string
     */
    private function renderCounter()
    {
        $result = Html::openTag('div', $this->attributesCounter);
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
        $result = Html::openTag('tr', $this->attributesHeading);
        if ($this->tableConfig) {
            foreach ($this->tableConfig AS $key => $row) {
                $result .= Html::openTag('th');
                $result .= isset($row['header']) ? $row['header'] : $key;
                $result .= Html::closeTag('th');
            }
        } else {
            $result .= Html::openTag('td', ['style' => 'text-align:center']) . $this->emptyText . Html::closeTag('td');
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
            $result .= Html::beginForm(null, 'get');
            $result .= Html::openTag('tr');
            foreach ($this->tableConfig AS $key => $row) {
                $result .= Html::openTag('td');
                $result .= isset($row['filter']) ? $row['filter'] : null;
                $result .= Html::closeTag('td');
            }
            $result .= Html::closeTag('tr');
            $result .= Html::endForm();
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
            foreach ($this->tableConfig AS $key => $row) {
                $result .= Html::openTag('td');
                if (isset($row['class']) AND is_subclass_of($row['class'], 'Micro\widgets\GridColumn')) {
                    $primaryKey = $elem[isset($row['key']) ? $row['key'] : 'id'];
                    $result .= new $row['class'](
                        $row + ['str' => isset($elem) ? $elem : null, 'pKey' => $primaryKey]
                    );
                } elseif (isset($row['value'])) {
                    $data = $elem;
                    $result .= eval('return ' . $row['value'] . ';');
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