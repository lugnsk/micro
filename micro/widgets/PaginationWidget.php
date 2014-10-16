<?php /** MicroPaginationWidget */

namespace Micro\widgets;

use Micro\base\Widget;
use Micro\wrappers\Html;

/**
 * PaginationWidget class file.
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
class PaginationWidget extends Widget
{
    /** @var int $countRows count rows */
    public $countRows = 0;
    /** @var int $limit limit rows per page */
    public $limit = 10;
    /** @var int $currentPage current page */
    public $currentPage = 1;
    /** @var string $url url for pagination links */
    public $url = '';
    /** @var array $attributes attributes for list */
    public $attributes = [];
    /** @var array $attributesElement attributes for list element */
    public $attributesElement = [];
    /** @var array $attributesLink attributes for links */
    public $attributesLink = [];

    /** @var int $totalPages count pages */
    private $totalPages = 0;


    /**
     * Initialize widget
     *
     * @access public
     * @result void
     */
    public function init()
    {
        if ($this->countRows < 1) {
            $this->totalPages = 0;
            $this->currentPage = 0;
            return;
        }

        if ($this->limit < 10) {
            $this->limit = 10;
        }

        $this->totalPages = $this->countRows / $this->limit;

        if ($this->totalPages == 0) {
            $this->totalPages = 1;
        }

        if ($a = ($this->countRows % $this->limit)) {
            $this->totalPages++;
        }

        if ($this->currentPage < 0) {
            $this->currentPage = 0;
        }

        if ($this->currentPage > $this->totalPages) {
            $this->currentPage = $this->totalPages;
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
        $items = [];
        if ($this->totalPages > 0) {
            for ($i=0; ($i+1) <= $this->totalPages; $i++) {
                if ($i != $this->currentPage) {
                    $items[] = [
                        'text'=>Html::href( $i+1, $this->url.$i, $this->attributesLink),
                        'attr'=>$this->attributesElement
                    ];
                } else {
                    $items[] = [
                        'text'=>Html::href( $i+1, $this->url.$i, $this->attributesLink),
                        'attr'=>$this->attributesElement + ['class'=>'active']
                    ];
                }
            }
        }
        echo Html::lists($items, $this->attributes);
    }
}