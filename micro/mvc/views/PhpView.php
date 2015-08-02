<?php /** MicroPhpView */

namespace Micro\mvc\views;

use Micro\base\Exception;
use Micro\web\Language;

/**
 * Class PhpView
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage mvc/views
 * @version 1.0
 * @since 1.0
 */
class PhpView extends View
{
    /** @var string Layout to render */
    public $layout;
    /** @var string $view View name */
    public $view;
    /** @var string $path Path to view */
    public $path;
    /** @var string $data Return data */
    public $data = '';

    /**
     * Render partial
     *
     * @access public
     *
     * @param string $view view name
     *
     * @return string
     * @throws \Micro\base\Exception
     */
    public function renderPartial($view)
    {
        $lay = $this->layout;
        $wi = $this->view;

        $this->layout = null;
        $this->view = $view;
        $output = $this->render();
        $this->layout = $lay;
        $this->view = $wi;

        return $output;
    }

    /**
     * Render insert data into view
     *
     * @access protected
     *
     * @return string
     * @throws \Micro\base\Exception
     */
    public function render()
    {
        if (!$this->view) {
            return false;
        }

        return $this->renderRawData(
            ($this->data) ?: $this->renderFile($this->getViewFile($this->view), $this->params)
        );
    }

    /**
     * Render raw data in layout
     *
     * @access public
     * @global       Micro
     * @global       Container
     *
     * @param string $data arguments array
     *
     * @return string
     * @throws \Micro\base\Exception
     */
    public function renderRawData($data = '')
    {
        $layoutPath = null;
        if ($this->layout) {
            $layoutPath = $this->getLayoutFile($this->container->kernel->getAppDir(), $this->module);
        }

        if ($layoutPath) {
            $data = $this->insertStyleScripts($this->renderFile($layoutPath, ['content' => $data]));
        }

        return $data;
    }

    /**
     * Get layout path
     *
     * @access protected
     *
     * @param string $baseDir path to base dir
     * @param string $module module name
     *
     * @return string
     * @throws Exception
     */
    protected function getLayoutFile($baseDir, $module)
    {
        $layout = $baseDir . '/' . (($module) ? $module . '/' : $module);
        $afterPath = 'views/layouts/' . ucfirst($this->layout) . '.php';

        if (!file_exists($layout . $afterPath)) {
            if (file_exists($baseDir . '/' . $afterPath)) {
                return $baseDir . '/' . $afterPath;
            }
            throw new Exception($this->container, 'Layout ' . ucfirst($this->layout) . ' not found.');
        }

        return $layout . $afterPath;
    }

    /**
     * Render file by path
     *
     * @access protected
     *
     * @param string $fileName file name
     * @param array $data arguments array
     *
     * @return string
     * @throws Exception widget not declared
     */
    protected function renderFile($fileName, array $data = [])
    {
        /** @noinspection OnlyWritesOnParameterInspection */
        /** @noinspection PhpUnusedLocalVariableInspection */
        $lang = new Language($this->container, $fileName);
        extract($data, EXTR_PREFIX_SAME || EXTR_REFS, 'data');
        ob_start();

        /** @noinspection PhpIncludeInspection */
        include str_replace('\\', '/', $fileName);

        if ($GLOBALS['widgetStack']) {
            throw new Exception($this->container, count($GLOBALS['widgetStack']) . ' widgets not endings.');
        }

        return ob_get_clean();
    }

    /**
     * Get view file
     *
     * @access private
     *
     * @param string $view view file name
     *
     * @return string
     * @throws Exception
     */
    private function getViewFile($view)
    {
        $calledClass = $this->path;

        // Calculate path to view
        if (substr($calledClass, 0, strpos($calledClass, '\\')) === 'App') {
            $path = $this->container->kernel->getAppDir();
        } else {
            $path = $this->container->kernel->getMicroDir();
        }

        $cl = strtolower(dirname(str_replace('\\', '/', $calledClass)));
        $cl = substr($cl, strpos($cl, '/'));

        if ($this->asWidget) {
            $path .= $cl . '/views/' . $view . '.php';
        } else {
            $className = str_replace('controller', '',
                strtolower(basename(str_replace('\\', '/', '/' . $this->path))));
            $path .= dirname($cl) . '/views/' . $className . '/' . $view . '.php';
        }

        $path = str_replace('//', '/', $path);

        if (!file_exists($path)) {
            throw new Exception($this->container, 'View path `' . $path . '` not exists.');
        }

        return $path;
    }
}
