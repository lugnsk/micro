<?php /** MicroPhpView */

namespace Micro\mvc\views;

use Micro\Micro;
use Micro\web\Language;
use Micro\base\Registry;
use Micro\base\Exception;

/**
 * Class PhpView
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage mvc/views
 * @version 1.0
 * @since 1.0
 */
class PhpView extends View {
    public $layout;
    public $view;
    public $path;
    public $data='';

    /**
     * Render insert data into view
     *
     * @access protected
     * @return string
     */
    public function render() {
        if (empty($this->view)) {
            return false;
        }
        return $this->renderRawData(
            ($this->data) ? $this->data : $this->renderFile($this->getViewFile($this->view), $this->params)
        );
    }
    /**
     * Render partial
     *
     * @access public
     * @param string $view view name
     * @return string
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
     * Render file by path
     *
     * @access protected
     * @param string $fileName file name
     * @param array $data arguments array
     * @return string
     * @throws Exception widget not declared
     */
    protected function renderFile($fileName, $data = [])
    {
        $lang = new Language($fileName);

        extract($data, EXTR_PREFIX_SAME, 'data');
        ob_start();
        include str_replace('\\', '/', $fileName);

        if (!empty($this->widgetStack)) {
            throw new Exception(count($this->widgetStack) . ' widgets not endings.');
        }

        return ob_get_clean();
    }
    /**
     * Render raw data in layout
     *
     * @access public
     * @global Micro
     * @global Registry
     * @param string $data arguments array
     * @return string
     */
    public function renderRawData($data = '')
    {
        $layoutPath = null;
        if ($this->layout) {
            $layoutPath = $this->getLayoutFile(
                Micro::getInstance()->config['AppDir'],
                Registry::get('request')->getModules()
            );
        }

        if ($layoutPath) {
            $data = $this->insertStyleScripts($this->renderFile($layoutPath, ['content' => $data]));
        }
        return $data;
    }

    /**
     * Get view file
     *
     * @access private
     * @param string $view view file name
     * @return string
     */
    private function getViewFile($view)
    {
        $calledClass = $this->path;

        // Calculate path to view
        if (substr($calledClass, 0, strpos($calledClass, '\\')) == 'App') {
            $path = Micro::getInstance()->config['AppDir'];
        } else {
            $path = Micro::getInstance()->config['MicroDir'];
        }

        $cl = strtolower(dirname(strtr($calledClass, '\\', '/')));
        $cl = substr($cl, strpos($cl, '/'));

        if ($this->asWidget) {
            $path .= $cl . '/views/' . $view . '.php';
        } else {
            $className = str_replace('controller', '',
                strtolower(basename(str_replace('\\', '/', '/' . $this->path))));
            $path .= dirname($cl) . '/views/' . $className . '/' . $view . '.php';
        }
        return $path;
    }
    /**
     * Get layout path
     *
     * @access protected
     * @param string $baseDir path to base dir
     * @param string $module module name
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
            throw new Exception('Layout ' . ucfirst($this->layout) . ' not found.');
        }
        return $layout . $afterPath;
    }
}