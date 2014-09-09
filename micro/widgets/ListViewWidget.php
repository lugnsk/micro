<?php

namespace Micro\widgets;

use Micro\base\Exception;
use Micro\base\Registry;
use Micro\base\Widget;
use Micro\db\Query;
use Micro\Micro;

class ListViewWidget extends Widget
{
    public $query=null;
    public $controller=null;
    public $view=null;
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

    protected $pathView='';


    public function init()
    {
        if (!$this->query instanceof Query) {
            throw new Exception('Query not defined or error type');
        }
        if (!$this->controller OR !$this->view) {
            throw new Exception('Controller or view not defined');
        }

        $cls = str_replace('\\','/', get_class($this->controller));
        $this->pathView = str_replace('App', Micro::getInstance()->config['AppDir'], dirname($cls)).'/../views/';
        $this->pathView .= strtolower(str_replace('Controller','',basename($cls))).'/'.$this->view.'.php';

        if (!file_exists($this->pathView)) {
            throw new Exception('View path not valid: '.$this->pathView);
        }
        if ($this->limit < 10) {
            $this->limit = 10;
        }

        $this->paginationConfig['countRows'] = $this->rowCount;
        $this->paginationConfig['limit'] = $this->limit;
        $this->paginationConfig['currentPage'] = $this->page;
    }
    public function run()
    {
        foreach ($this->query->run() AS $element) {
            include $this->pathView;
        }
    }
}