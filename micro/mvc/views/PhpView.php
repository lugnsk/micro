<?php

namespace Micro\mvc\views;

use Micro\Micro;
use Micro\web\Language;
use Micro\base\Registry;
use Micro\wrappers\Html;
use Micro\base\Exception;

class PhpView implements View {
    public $asWidget=false;
    public $layout;
    public $view;
    public $path;

    public $styleScripts = [];
    public $params=[];
    public $data='';

    /**
     * Add parameter into view
     *
     * @access public
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function addParameter($name, $value) {
        $this->params[$name] = $value;
    }

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
        return $this->renderRawData($this->renderFile($this->getViewFile($this->view), $this->params));
    }

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
     * Render raw data in layout
     *
     * @access protected
     * @global Micro
     * @global Registry
     * @param string $data arguments array
     * @return string
     */
    protected function renderRawData($data = '')
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
     * Insert styles and scripts into cache
     *
     * @access protected
     * @param string $cache cache of generated page
     * @return string
     */
    protected function insertStyleScripts($cache)
    {
        $heads = '';
        $ends = '';
        $result = '';

        foreach ($this->styleScripts AS $element) {
            if ($element['isHead']) {
                $heads .= $element['body'];
            } else {
                $ends .= $element['body'];
            }
        }

        $positionHead = strpos($cache, Html::closeTag('head'));
        $positionBody = strpos($cache, Html::closeTag('body'), $positionHead);

        $result .= substr($cache, 0, $positionHead);
        $result .= $heads;
        $result .= substr($cache, $positionHead, $positionBody);
        $result .= $ends;
        $result .= substr($cache, $positionHead + $positionBody);

        return $result;
    }
    /**
     * Register JS script
     *
     * @access public
     * @param string $source file name
     * @param bool $isHead is head block
     * @return void
     */
    public function registerScript($source, $isHead = true)
    {
        $this->styleScripts[] = [
            'isHead' => $isHead,
            'body' => Html::script($source)
        ];
    }
    /**
     * Register JS file
     *
     * @access public
     * @param string $source file name
     * @param bool $isHead is head block
     * @return void
     */
    public function registerScriptFile($source, $isHead = true)
    {
        $this->styleScripts[] = [
            'isHead' => $isHead,
            'body' => Html::scriptFile($source)
        ];
    }
    /**
     * Register CSS code
     *
     * @access public
     * @param string $source file name
     * @param bool $isHead is head block
     * @return void
     */
    public function registerCss($source, $isHead = true)
    {
        $this->styleScripts[] = [
            'isHead' => $isHead,
            'body' => Html::css($source)
        ];
    }
    /**
     * Register CSS file
     *
     * @access public
     * @param string $source file name
     * @param bool $isHead is head block
     * @return void
     */
    public function registerCssFile($source, $isHead = true)
    {
        $this->styleScripts[] = [
            'isHead' => $isHead,
            'body' => Html::cssFile($source)
        ];
    }

    public function __toString() {
        return ''.$this->render();
    }
}