<?php /** MicroGridViewWidget */

namespace Micro\widgets;

use Micro\base\Type;
use Micro\db\Query;
use Micro\mvc\Widget;
use Micro\wrappers\Html;
use Micro\base\Exception;

/**
 * GridViewWidget class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage widgets
 * @version 1.0
 * @since 1.0
 */
class GridViewWidget extends Widget
{
    /** @var int $page Current page on table */
    public $page = 0;
    /** @var int $limit Limit current rows */
    public $limit = 10;
    /** @var bool $filters Usage filters */
    public $filters = true;
    /** @var string $template Template render */
    public $template = '{counter}{table}{pager}';
    /** @var string $templateTable Template table render */
    public $templateTable = '{headers}{filters}{rows}';
    /** @var string $textCounter text for before counter */
    public $counterText = 'Sum: ';
    /** @var string $emptyText text to render if rows not found */
    public $emptyText = 'Elements not found';
    /** @var array $attributesEmpty Attributes for empty text */
    public $attributesEmpty = [];
    /** @var array $attributes attributes for table */
    public $attributes = [];
    /** @var array $attributesCounter attributes for counter */
    public $attributesCounter = [];
    /** @var array $attributesHeading attributes for heading */
    public $attributesHeading = [];
    /** @var array $tableConfig table configuration */
    public $tableConfig = [];
    /** @var array $paginationConfig parameters for PaginationWidget */
    public $paginationConfig = [];

    /** @var array $rows Rows from data */
    protected $rows;
    /** @var array $fields Fields of data */
    protected $fields = [];
    /** @var int $rowsCount Count rows */
    protected $rowsCount = 0;
    /** @var int $totalCount Total count data */
    protected $totalCount = 0;
    /** @var string $filterPrefix prefix for filter name */
    protected $filterPrefix;


    /**
     * Re-declare widget constructor
     *
     * @access public
     *
     * @param array $args arguments
     *
     * @result void
     * @throws Exception
     */
    public function __construct( array $args = [] )
    {
        parent::__construct( $args );

        if (empty($args['data'])) {
            throw new Exception('Argument "data" not initialized into GridViewWidget');
        }

        $this->limit = ($this->limit < 10) ? 10 : $this->limit;
        $this->page  = ($this->page < 0)   ? 0  : $this->page;

        if ($args['data'] instanceof Query) {
            if (strlen($args['data']->objectName)) {
                $cls = $args['data']->objectName;
                $args['data']->table = $cls::tableName();
            } elseif (!$args['data']->table) {
                throw new Exception('Data query not set table or objectName');
            }

            $this->filterPrefix = $args['data']->table;

            if ($args['data']->having || $args['data']->group) {
                $res = new Query;
                $res->select = 'COUNT(*)';
                $res->table = '(' . $args['data']->getQuery() . ') micro_count';
                $res->single = true;
            } else {
                $res = clone $args['data'];
                $res->objectName = null;
                $res->select = 'COUNT(*)';
                $res->single = true;
            }

            $this->totalCount     = $res->run()[0];
            $args['data']->ofset  = $this->page*$this->limit;
            $args['data']->limit  = $this->limit;
            $args['data']     = $args['data']->run( $args['data']->objectName ? \PDO::FETCH_CLASS : \PDO::FETCH_ASSOC );
        } else {
            $this->totalCount = count($args['data']);
            $args['data'] = array_slice($args['data'], $this->page * $this->limit, $this->limit);
        }

        foreach ($args['data'] AS $model) {
            $this->rows[] = is_subclass_of($model, 'Micro\db\Model') ? $model : (object)$model;
        }
    }

    /**
     * Initialize widget
     *
     * @access public
     *
     * @result void
     */
    public function init()
    {
        $this->filterPrefix = ucfirst( $this->filterPrefix ?: 'data' . $this->totalCount );
        $this->fields = (null !== $this->rows) ? array_keys(Type::getVars($this->rows[0])) : [];
        $this->rowsCount = count($this->rows);

        $this->paginationConfig['countRows']   = $this->totalCount;
        $this->paginationConfig['limit']       = $this->limit;
        $this->paginationConfig['currentPage'] = $this->page;

        $this->tableConfig = $this->tableConfig ?: $this->fields;
    }

    /**
     * Running widget
     *
     * @access public
     *
     * @return string
     */
    public function run()
    {
        if (!$this->rows) {
            return Html::openTag('div', $this->attributesEmpty) . $this->emptyText . Html::closeTag('div');
        }

        ob_start();

        echo str_replace(
            ['{counter}', '{pager}', '{table}'],
            [ $this->getCounter(), $this->getPager(), $this->getTable() ],
            $this->template
        );

        return ob_get_clean();
    }

    /**
     * Get counter
     *
     * @access protected
     *
     * @return string
     */
    protected function getCounter()
    {
        return Html::openTag('div', $this->attributesCounter) .
               $this->counterText . $this->totalCount . Html::closeTag('div');
    }

    /**
     * Get pager
     *
     * @access protected
     *
     * @return string
     */
    protected function getPager()
    {
        if (!$this->rows) {
            return '';
        }

        $pager = new PaginationWidget($this->paginationConfig);
        $pager->init();
        return $pager->run();
    }

    /**
     * Get table
     *
     * @access protected
     *
     * @return string
     */
    protected function getTable()
    {
        $table = str_replace(
            ['{headers}','{filters}','{rows}'],
            [$this->renderHeading(),$this->renderFilters(),$this->renderRows()],
            $this->templateTable
        );

        return Html::openTag('table', $this->attributes) . $table . Html::closeTag('table');
    }

    /**
     * Render heading
     *
     * @access protected
     *
     * @return string
     */
    protected function renderHeading()
    {
        if (!$this->tableConfig) {
            return '';
        }

        $result = Html::openTag('tr', $this->attributesHeading);
        foreach ($this->tableConfig AS $key=>$row) {
            $result .= Html::openTag('th', (!empty($row['attributesHeader']) ? $row['attributesHeader'] : []) );
            $result .= !is_array($row) ? ucfirst($row) : (!empty($row['header']) ? $row['header'] : ucfirst($key));
            $result .= Html::closeTag('th');
        }
        $result .= Html::closeTag('tr');

        return $result;
    }

    /**
     * Render filters
     *
     * @access protected
     *
     * @return null|string
     */
    protected function renderFilters()
    {
        if (!$this->filters) {
            return '';
        }

        $result  = Html::beginForm(null, 'get');
        $result .= Html::openTag('tr');

        foreach ($this->tableConfig AS $key=>$row) {
            if (is_array($row) && array_key_exists('filter', $row)) {
                if (null !== $row['filter']) {
                    $result .= Html::openTag('td', (!empty($row['attributesFilter'])?$row['attributesFilter']:[]) );
                    $result .= $row['filter'];
                    $result .= Html::closeTag('td');
                }
            } else {
                $buffer    = is_array($row) ? $key : $row;
                $fieldName = $this->filterPrefix . '[' . $buffer . ']';
                $fieldId   = $this->filterPrefix . '_' . $buffer;

                $val = !empty($_GET[ $this->filterPrefix ][$buffer]) ? $_GET[ $this->filterPrefix ][$buffer] : '';

                $result .= Html::openTag('td', (!empty($row['attributesFilter'])?$row['attributesFilter']:[]));
                $result .= Html::textField($fieldName, $val, [ 'id' => $fieldId ]);
                $result .= Html::closeTag('td');
            }
        }

        $result .= Html::closeTag('tr');
        $result .= Html::endForm();

        return $result;
    }

    /**
     * Render rows
     *
     * @access protected
     *
     * @return null|string
     */
    protected function renderRows()
    {
        $result = null;

        if (empty($this->rows)) {
            return Html::openTag('tr') .
                        Html::openTag('td', ['cols'=>count($this->keys)]) .
                            $this->emptyText .
                        Html::closeTag('td') .
                   Html::closeTag('tr');
        }

        foreach ($this->rows AS $data) {
            $result .= Html::openTag('tr');

            foreach ($this->tableConfig AS $key => $row) {
                $result .= Html::openTag('td', ( !empty($row['attributes']) ? $row['attributes'] : []) );

                if (!empty($row['class']) AND is_subclass_of($row['class'], 'Micro\widgets\GridColumn')) {
                    $primaryKey = $data->{ !empty($row['key']) ? $row['key'] : 'id' };
                    $result .= (new $row['class'](
                        $row + ['str' => (null === $data) ?: $data, 'pKey' => $primaryKey]
                    ));
                } elseif (!empty($row['value'])) {
                    $result .= eval('return ' . $row['value'] . ';');
                } else {
                    $buffer = is_array($row) ? $key : $row;

                    $result .= property_exists($data, $buffer) ? $data->$buffer : null;
                }
                $result .= Html::closeTag('td');
            }
            $result .= Html::closeTag('tr');
        }
        return $result;
    }
}














