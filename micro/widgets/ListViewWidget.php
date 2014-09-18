<?php /** MicroListViewWidget */

namespace Micro\widgets;

use Micro\base\Exception;
use Micro\base\Widget;
use Micro\db\Query;
use Micro\Micro;
use Micro\web\helpers\Html;

/**
 * ListViewWidget class file.
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
class ListViewWidget extends Widget
{
    /** @var string $query query to database */
    public $query=null;
    /** @var \Micro\base\Controller $controller caller controller */
    public $controller=null;
    /** @var string $view Name of view file */
    public $view=null;
    /** @var int $limit Limit current rows */
    public $limit = 10;
    /** @var int $page Current page on table */
    public $page = 1;
    /** @var array $paginationConfig parameters for PaginationWidget */
    public $paginationConfig = [];
    /** @var array $attributes attributes for dl */
    public $attributes = [];
    /** @var array $attributesElement attributes for dt */
    public $attributesElement = [];

    /** @var int $rowCount summary lines */
    protected $rowCount = 0;
    /** @var array Rows table */
    protected $rows = [];
    /** @var string $pathView Generate path to view file */
    protected $pathView='';


    /**
     * Initialize widget
     *
     * @access public
     * @result void
     */
    public function init()
    {
        if (!$this->query instanceof Query) {
            throw new Exception('Query not defined or error type');
        }
        if (!$this->controller OR !$this->view) {
            throw new Exception('Controller or view not defined');
        }
        if ($this->limit < 10) {
            $this->limit = 10;
        }

        $cls = str_replace('\\','/', get_class($this->controller));
        $this->pathView = str_replace('App', Micro::getInstance()->config['AppDir'], dirname($cls));
        $this->pathView .= '/../views/'.strtolower(str_replace('Controller','',basename($cls)));
        $this->pathView .= '/'.$this->view.'.php';

        if (!file_exists($this->pathView)) {
            throw new Exception('View path not valid: '.$this->pathView);
        }

        $this->rows = $this->query->run(\PDO::FETCH_ASSOC);
        $this->rowCount = count($this->rows);

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
        $st = $i = $this->page * $this->limit;

        echo Html::openTag('ul', $this->attributes);
        for ( ; $i < ($st + $this->limit); $i++) {
            if (isset($this->rows[$i])) {
                echo Html::openTag('li', $this->attributesElement);

                $element = $this->rows[$i];
                include $this->pathView;

                echo Html::closeTag('li');
            }
        }
        echo Html::closeTag('ul');
        echo $this->widget('Micro\widgets\PaginationWidget', $this->paginationConfig);
    }
}