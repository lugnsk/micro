<?php /** MicroAsset */

namespace Micro\Web;

use Micro\Base\Autoload;
use Micro\Base\Exception;
use Micro\File\FileHelper;
use Micro\Mvc\Views\IView;

/**
 * Asset class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Web
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
    /** @var array $required Required assets */
    public $required = [];
    /** @var array $excludes Excludes extensions */
    public $excludes = [];

    /** @var IView $view View for install current asset */
    protected $view;
    /** @var string $hash Unique directory to publish into assets dir */
    protected $hash;
    /** @var string $publishPath Publish path */
    protected $publishPath;
    /** @var array $published Published required extends */
    private $published = [];


    /**
     * Constructor asset
     *
     * @access public
     *
     * @param IView $view
     *
     * @result void
     * @throws \Micro\Base\Exception
     */
    public function __construct(IView $view)
    {
        $this->view = $view;

        if (!$this->sourcePath) {
            $this->sourcePath = dirname(Autoload::getClassPath(get_class($this)));
        }

        $this->hash = md5($this->sourcePath);

        $this->publishPath = '/' . (($dir = $view->container->assetsDirName) ? $dir : 'assets') . '/' . $this->hash;

        $web = $this->view->container->kernel->getWebDir();

        if (!file_exists($this->sourcePath)) {
            throw new Exception('Asset dir not exists: ' . $this->sourcePath);
        }

        if (!@mkdir($web . $this->publishPath, 0777) && !is_dir($web . $this->publishPath)) {
            throw new Exception('Could not access to publish dir: ' . $this->publishPath);
        }

        FileHelper::recurseCopyIfEdited($this->sourcePath, $web . $this->publishPath, $this->excludes);
    }

    /**
     * Send asset into view
     *
     * @access public
     * @return void
     */
    public function publish()
    {
        foreach ($this->required AS $require) {
            if (!in_array($require, $this->published, true) && class_exists($require)) {
                $this->published[] = $require;
                /** @var Asset $require */
                $require = new $require($this->view);
                $require->publish();
            }
        }

        if ($this->js) {
            if (is_string($this->js)) {
                $this->js = [$this->js];
            }
            foreach ($this->js AS $script) {
                $this->view->registerScriptFile($this->publishPath . $script, $this->isHead);
            }
        }
        if ($this->css) {
            if (is_string($this->css)) {
                $this->css = [$this->css];
            }
            foreach ($this->css AS $style) {
                $this->view->registerCssFile($this->publishPath . $style, $this->isHead);
            }
        }
    }
}
