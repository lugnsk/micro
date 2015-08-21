<?php

namespace Micro\mvc\views;

use Micro\base\Exception;
use Micro\base\IContainer;

/**
 * Interface IView
 * @package Micro\mvc\views
 *
 * @property IContainer $container
 */
interface IView
{
    /**
     * Add parameter into view
     *
     * @access public
     *
     * @param string $name parameter name
     * @param mixed $value parameter value
     *
     * @return void
     */
    public function addParameter($name, $value);

    /**
     * Widget
     *
     * @access public
     *
     * @param string $name widget name
     * @param array $options options array
     * @param bool $capture capture output
     *
     * @return string
     * @throws Exception
     */
    public function widget($name, array $options = [], $capture = false);

    /**
     * Begin widget
     *
     * @access public
     *
     * @param string $name widget name
     * @param array $options options array
     *
     * @return mixed
     * @throws Exception
     */
    public function beginWidget($name, array $options = []);

    /**
     * Ending widget
     *
     * @access public
     *
     * @param string $name widget name
     *
     * @throws Exception
     */
    public function endWidget($name = '');

    /**
     * Render
     *
     * @access public
     * @return mixed
     * @abstract
     */
    public function render();

    /**
     * Register JS script
     *
     * @access public
     *
     * @param string $source file name
     * @param bool $isHead is head block
     *
     * @return void
     */
    public function registerScript($source, $isHead = true);

    /**
     * Register JS file
     *
     * @access public
     *
     * @param string $source file name
     * @param bool $isHead is head block
     *
     * @return void
     */
    public function registerScriptFile($source, $isHead = true);

    /**
     * Register CSS code
     *
     * @access public
     *
     * @param string $source file name
     * @param bool $isHead is head block
     *
     * @return void
     */
    public function registerCss($source, $isHead = true);

    /**
     * Register CSS file
     *
     * @access public
     *
     * @param string $source file name
     * @param bool $isHead is head block
     *
     * @return void
     */
    public function registerCssFile($source, $isHead = true);
}
