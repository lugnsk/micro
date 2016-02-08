<?php /** MicroPaginationWidget */

namespace Micro\Widget;

use Micro\Mvc\Widget;
use Micro\Web\Html;

/**
 * PaginationWidget class file.
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
class PaginationWidget extends Widget
{
    /** @var int $countRows count rows */
    public $countRows = 0;
    /** @var int $currentPage current page */
    public $currentPage = 0;
    /** @var int $limit limit rows per page */
    public $limit = 10;
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
     *
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

        $this->totalPages = (int)($this->countRows / $this->limit);

        if ($this->countRows % $this->limit) {
            $this->totalPages++;
        }

        if ($this->totalPages === 0) {
            $this->totalPages = 1;
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
     *
     * @return string
     */
    public function run()
    {
        $items = [];
        if ($this->totalPages > 0) {
            for ($i = 1; $i <= $this->totalPages; $i++) {
                $items[] = [
                    'text' => Html::href($i, $this->url . ($i - 1), $this->attributesLink),
                    'attr' => array_merge(
                        $this->attributesElement,
                        ($i === (int)$this->currentPage + 1 ? ['class' => 'active'] : [])
                    )
                ];
            }
        }

        return Html::lists($items, $this->attributes);
    }
}
