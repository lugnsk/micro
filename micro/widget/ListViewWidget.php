<?php /** MicroListViewWidget */

namespace Micro\Widget;

use Micro\Base\Exception;
use Micro\Mvc\Models\IModel;
use Micro\Mvc\Models\IQuery;
use Micro\Mvc\Widget;
use Micro\Web\Html;

/**
 * ListViewWidget class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Widget
 * @version 1.0
 * @since 1.0
 */
class ListViewWidget extends Widget
{
    /** @var int $page Current page on table */
    public $page = 0;
    /** @var int $limit Limit current rows */
    public $limit = 10;
    /** @var string $template Template */
    public $template = '{counter}{elements}{pager}';
    /** @var string $counterText Text for counter */
    public $counterText = 'Sum: ';
    /** @var string $emptyText Text for empty message */
    public $emptyText = 'Elements not found';
    /** @var array $attributes attributes for dl */
    public $attributes = [];
    /** @var array $attributesElement attributes for element */
    public $attributesElement = [];
    /** @var array $attributesCounter attributes for counter */
    public $attributesCounter = [];
    /** @var array $attributesEmpty attributes for empty */
    public $attributesEmpty = [];
    /** @var array $paginationConfig parameters for PaginationWidget */
    public $paginationConfig = [];

    /** @var string $view Path to view file */
    protected $pathView = '';

    /** @var array $rows Rows from data */
    protected $rows;
    /** @var int $rowsCount Count rows */
    protected $rowsCount = 0;
    /** @var int $totalCount Total count data */
    protected $totalCount = 0;

    /**
     * Redeclare widget constructor
     *
     * @access public
     *
     * @param array $args Widget arguments
     *
     * @result void
     * @throws Exception
     */
    public function __construct(array $args = [])
    {
        parent::__construct($args);

        if (empty($args['data'])) {
            throw new Exception('Argument "data" not initialized into ListViewWidget');
        }
        if (empty($args['pathView'])) {
            throw new Exception('Argument "pathView" not initialized into ListViewWidget');
        }

        if (!is_int($this->page)) {
            $this->page = (int)$this->page;
        }
        if ($this->limit < 10) {
            $this->limit = 10;
        }
        if ($this->page < 0) {
            $this->page = 0;
        }

        if ($args['data'] instanceof IQuery) {
            /** @var |Query $args ['data'] */
            if ($args['data']->objectName) {
                /** @var IModel $cls */
                $cls = $args['data']->objectName;
                $args['data']->table = $cls::tableName();
            } elseif (!$args['data']->table) {
                throw new Exception('Data query not set table or objectName');
            }

            $select = $args['data']->select;

            $args['data']->select = 'COUNT(id)';
            $args['data']->single = true;
            $this->totalCount = $args['data']->run(\PDO::FETCH_BOTH)[0];

            $args['data']->select = $select;
            $args['data']->ofset = $this->page * $this->limit;
            $args['data']->limit = $this->limit;
            $args['data']->single = false;
            $args['data'] = $args['data']->run();
        } else {
            $this->totalCount = count($args['data']);
            $this->page = $this->page === 0 ? 1 : $this->page;
            $args['data'] = array_slice($args['data'], $this->page * $this->limit, $this->limit);
        }

        foreach ($args['data'] AS $model) {
            $this->rows[] = is_subclass_of($model, 'Micro\\Mvc\\Models\\IModel') ? $model : (object)$model;
        }
    }

    /**
     * Initialized widget
     *
     * @access public
     *
     * @return void
     * @throws Exception
     */
    public function init()
    {
        if (!file_exists($this->pathView)) {
            throw new Exception('View path not valid: ' . $this->pathView);
        }

        $this->rowsCount = count($this->rows);

        $this->paginationConfig['countRows'] = $this->totalCount;
        $this->paginationConfig['limit'] = $this->limit;
        $this->paginationConfig['currentPage'] = $this->page;
    }

    /**
     * Running widget
     *
     * @access public
     *
     * @return void
     */
    public function run()
    {
        echo str_replace(
            ['{counter}', '{elements}', '{pager}'],
            [$this->getCounter(), $this->getElements(), $this->getPager()],
            $this->template
        );
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
     * Get elements
     *
     * @access protected
     *
     * @return string
     */
    protected function getElements()
    {
        if (!$this->rows) {
            return Html::openTag('div', $this->attributesEmpty) . $this->emptyText . Html::closeTag('div');
        }

        ob_start();
        echo Html::openTag('ul', $this->attributes);


        /** @noinspection PhpUnusedLocalVariableInspection */
        foreach ($this->rows AS $element) {
            echo Html::openTag('li', $this->attributesElement);

            /** @noinspection PhpIncludeInspection */
            include $this->pathView;

            echo Html::closeTag('li');
        }

        echo Html::closeTag('ul');

        return ob_get_clean();
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
}
