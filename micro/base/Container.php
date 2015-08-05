<?php /** MicroContainer */

namespace Micro\base;

/**
 * Container class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class Container extends \stdClass implements IContainer
{
    /** @var array $data data */
    protected $data = [];
    /** @var array $config Configs */
    protected $config = [];
    /** @var array $components Components config */
    protected $components = [];


    /**
     * Load more configs from file
     *
     * @access public
     *
     * @param string $filename
     *
     * @return void
     */
    public function load($filename)
    {
        if (file_exists($filename = $this->kernel->getAppDir() . $filename)) {
            /** @noinspection PhpIncludeInspection */
            $this->config = array_merge_recursive($this->config, require $filename);
            $this->components = array_merge_recursive($this->components, $this->config['components']);
            unset($this->config['components']);
        }
    }

    /**
     * Is set component or option name into Container
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
        if (array_key_exists($name, $this->components)) {
            return true;
        }

        return false;
    }

    /**
     * Get Container value
     *
     * @access public
     *
     * @param string $name element name
     *
     * @return mixed
     */
    public function __get($name = '')
    {
        if (!empty($this->config[$name])) {
            return $this->config[$name];
        }

        if (empty($this->data[$name]) && !$this->configure($name)) {
            return false;
        }

        return $this->data[$name];
    }

    /**
     * Set attribute
     *
     * @access public
     *
     * @param string $name Name attribute
     * @param mixed $component Component or option
     *
     * @return void
     */
    public function __set($name, $component)
    {
        $this->data[$name] = $component;
    }

    /**
     * Get component's
     *
     * @access public
     *
     * @param string|null $name name element to initialize
     *
     * @return bool
     */
    public function configure($name = null)
    {
        if (0 === count($this->components)) {
            return false;
        }

        if ($name === null) {
            foreach ($this->components AS $name => $options) {
                if (!$this->loadComponent($name, $options)) {
                    return false;
                }
            }

            return true;
        }

        if (empty($this->components[$name])) {
            return false;
        }

        if (!$this->loadComponent($name, $this->components[$name])) {
            return false;
        }

        return true;
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
     */
    public function loadComponent($name, $options)
    {
        if (empty($options['class']) || !class_exists($options['class'])) {
            return false;
        }
        $className = $options['class'];

        if (empty($options['arguments'])) {
            $this->data[$name] = new $className;

            return true;
        }

        /** @noinspection AlterInForeachInspection */
        foreach ($options['arguments'] AS $key => &$val) {
            if ($val[0] === '@' && is_string($options['arguments'][$key])) {
                if ($val === '@this') {
                    $val = $this;
                } else {
                    if (null === $this->{substr($val, 1)}) {
                        return false;
                    }
                    $val = $this->__get(substr($val, 1));
                }
            }
        }
        $this->data[$name] = new $className($options['arguments']);

        return true;
    }
}
