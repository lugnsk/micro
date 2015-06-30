<?php /** MicroRegistry */

namespace Micro\base;

/**
 * Registry class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class Registry
{
    /** @var array $data registry data */
    protected $data = [];
    /** @var array $config Config array */
    protected $config = [];


    /**
     * Load more configs from file
     *
     * @access public
     *
     * @param string $filename
     *
     * @return void
     */
    public function load( $filename )
    {
        if ( file_exists($this->kernel->getAppDir() . $filename) ) {
            $this->config = array_merge_recursive($this->config, require $this->kernel->getAppDir() . $filename);
        }
    }

    /**
     * Set attribute
     *
     * @access public
     *
     * @param string $name Name attribute
     * @param mixed  $component Component or option
     *
     * @return void
     */
    public function __set($name, $component)
    {
        $this->data[$name] = $component;
    }

    /**
     * Is set component or option name into registry
     *
     * @access public
     *
     * @param string $name Name attribute
     *
     * @return bool
     */
    public function __isset($name)
    {
        if (array_key_exists($name, $this->config)) {
            return true;
        }
        if (array_key_exists($name, $this->data)) {
            return true;
        }
        if (array_key_exists($name, $this->config['components'])) {
            return true;
        }

        return false;
    }

    /**
     * Get registry value
     *
     * @access public
     *
     * @param string $name element name
     *
     * @return mixed
     * @throws Exception
     */
    public function __get($name = '')
    {
        if (!empty($this->config[$name])) {
            return $this->config[$name];
        }

        if (empty($this->data[$name])) {
            $this->configure($name);
        }

        return $this->data[$name];
    }

    /**
     * Get component's
     *
     * @access public
     *
     * @param string|null $name name element to initialize
     *
     * @return void
     * @throws \Micro\base\Exception
     */
    public function configure($name = null)
    {
        if (empty($this->config['components'])) {
            throw new Exception($this, 'Components not configured');
        }

        if ($name === null) {
            foreach ($this->config['components'] AS $name => $options) {
                if (!$this->loadComponent($name, $options)) {
                    throw new Exception($this, 'Class ' . $name . ' error loading.');
                }
            }
            return;
        }

        if (empty($this->config['components'][$name])) {
            throw new Exception($this, 'Class ' . $name . ' not configured.');
        }

        if (!$this->loadComponent($name, $this->config['components'][$name])) {
            throw new Exception($this, 'Class ' . $name . ' error loading.');
        }

    }

    /**
     * Load component
     *
     * @access public
     *
     * @param string $name component name
     * @param array $options component configs
     *
     * @return bool
     * @throws Exception
     */
    public function loadComponent($name, $options)
    {
        if (empty($options['class']) OR !class_exists($options['class'])) {
            return false;
        }
        $className = $options['class'];

        if (empty($options['arguments'])) {
            $this->data[$name] = new $className;
            return true;
        }

        foreach ($options['arguments'] AS $key => &$val) {
            if (is_string($val) && $val{0} === '@') {
                if ($val === '@this') {
                    $val = $this;
                } else {
                    $option = substr($val, 1);
                    if (!array_key_exists($option, $this->__get($option))){
                        throw new Exception($this, 'Argument `' . $option . '` not found into container');
                    }

                    $val = $this->__get( $option );
                }
            }
        }
        $this->data[$name] = new $className( $options['arguments'] );

        return true;
    }
}
