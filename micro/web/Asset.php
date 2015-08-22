<?php /** MicroAsset */

namespace Micro\web;

use Micro\file\FileHelper;
use Micro\mvc\views\IView;

/**
 * Asset class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web
 * @version 1.0
 * @since 1.0
 */
class Asset
{
    /** @var string $sourcePath Full-path to source asset dir */
    public $sourcePath;

    /** @var bool $isHead Is a publish into head block */
    public $isHead = true;
    /** @var array $js JavaScript files links */
    public $js = [];
    /** @var array $css CSS files links */
    public $css = [];

    /** @var IView $view View for install current asset */
    protected $view;
    /** @var string $hash Unique directory to publish into assets dir */
    protected $hash;
    /** @var string $publishPath Published path */
    protected $publishPath;


    /**
     * Constructor asset
     *
     * @access public
     *
     * @param IView $view
     *
     * @result void
     */
    public function __construct(IView $view)
    {
        $this->view = $view;

        $this->hash = md5($this->sourcePath);

        $this->publishPath = '/' . (($dir = $view->container->assetsDirName) ? $dir : 'assets') . '/' . $this->hash;

        $web = $this->view->container->kernel->getWebDir();

        if (!file_exists($web . $this->publishPath)) {
            mkdir($web . $this->publishPath, 0x777);
        }

        FileHelper::recurseCopyIfEdited($this->sourcePath, $web . $this->publishPath);
    }

    /**
     * Send asset into view
     *
     * @access public
     * @return void
     */
    public function publish()
    {
        if ($this->js) {
            if (is_string($this->js)) {
                $this->js = [$this->js];
            }
            foreach ($this->js AS $script) {
                $this->view->registerScriptFile($script, $this->isHead);
            }
        }
        if ($this->css) {
            if (is_string($this->css)) {
                $this->css = [$this->css];
            }
            foreach ($this->css AS $style) {
                $this->view->registerCssFile($style, $this->isHead);
            }
        }
    }
}
