<?php /** MicroAssets */

namespace Micro\web;

use Micro\base\Container;
use Micro\base\File as MFile;

/**
 * Assets class file.
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
class Assets
{
    protected $container;

    /** @var string $assetDir directory for assets */
    private $assetDir = 'assets';
    /** @var string $hash hash for asset path */
    private $hash = '';
    /** @var string $directory asset directory */
    private $directory = '';
    /** @var string $sourceDir source asset dir */
    private $sourceDir = '';
    /** @var string $publishDir public of project dir */
    private $publishDir = '';


    /**
     * Constructor for class
     *
     * @access public
     *
     * @param Container $Container
     * @param string $directory directory of assets
     *
     * @result void
     */
    public function __construct(Container $Container, $directory = '')
    {
        $this->container = $Container;
        $this->directory = rtrim($directory, '/') . '/assets';
        $this->hash = md5($this->directory);

        $tmp = '/' . $this->assetDir . '/' . $this->hash;
        $this->publishDir = $this->container->kernel->HtmlDir . $tmp;
        $this->sourceDir = $this->container->kernel->WebDir . $tmp;
    }

    /**
     * Publication directory or files
     *
     * @access public
     *
     * @param string $exclude exclude files
     *
     * @return void
     */
    public function publish($exclude = '.php')
    {
        $hashDir = $this->getSourceDir();

        if (!file_exists($hashDir)) {
            mkdir($hashDir, 0777);
        }

        if (is_dir($this->directory)) {
            MFile::recurseCopyIfEdited($this->directory, $this->sourceDir);
        } else {
            if (substr($hashDir, strlen($hashDir) - strlen($exclude)) !== $exclude) {
                if (!file_exists($hashDir)) {
                    copy($this->directory, $hashDir);
                    chmod($hashDir, 0666);
                } elseif (filemtime($this->directory) !== filemtime($hashDir)) {
                    copy($this->directory, $hashDir);
                    chmod($hashDir, 0666);
                }
            }
        }
    }

    /**
     * Get source directory
     *
     * @access public
     * @return string
     */
    public function getSourceDir()
    {
        return $this->sourceDir;
    }

    /**
     * Get publish directory
     *
     * @access public
     * @return string
     */
    public function getPublishDir()
    {
        return $this->publishDir;
    }
}