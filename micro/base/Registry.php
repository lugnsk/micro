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
    protected $appDir;

    /** @var array $data registry data */
    protected $data = [];
    protected $config = [];


    public function __construct( $path )
    {
        $this->appDir = $path;
    }

    public function load( $filename )
    {
        $this->config = require $this->appDir . $filename;
    }

    public function __set($name, $component)
    {
        $this->data[$name] = $component;
    }

    /**
     * Get registry value
     *
     * @access public
     *
     * @param string $name element name
     *
     * @return mixed
     * @static
     */
    public function __get($name = '')
    {
        if (!$this->__isset($name)) {
            return false;
        }

        if (!empty($this->config[$name])) {
            return $this->config[$name];
        }

        if (empty($this->data[$name])) {
            $this->configure($name);
        }

        return $this->data[$name];
    }
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
     * Get component's
     *
     * @access public
     *
     * @param string|null $name name element to initialize
     *
     * @throws \Micro\base\Exception
     * @static
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
     * @static
     */
    public function loadComponent($name, $options)
    {
        if (empty($options['class']) OR !class_exists($options['class'])) {
            return false;
        }

        if (!empty($options['depends'])) {
            if (is_array($options['depends'])) {
                foreach ($options['depends'] AS $depend) {
                    $this->configure($depend);
                }
            } else {
                $this->configure($options['depends']);
            }
        }

        $className = $options['class'];
        unset($options['class']);

        $this->data[$name] = new $className($options);
        return true;
    }
}
