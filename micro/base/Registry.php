<?php /** MicroRegistry */

namespace Micro\base;

use \Micro\Micro;

/**
 * Registry class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 * @final
 */
final class Registry
{
    /**
     * Disable construct
     *
     * @access protected
     * @result void
     */
    protected function __construct()
    {
    }

    /**
     * Disable clone
     *
     * @access protected
     * @return void
     */
    protected function __clone()
    {
    }

    /**
     * Get registry value
     *
     * @access public
     * @param string $name element name
     * @return mixed
     * @static
     */
    public static function get($name = '')
    {
        self::configure($name);
        return (isset($GLOBALS[$name])) ? $GLOBALS[$name] : null;
    }

    /**
     * Set registry value
     *
     * @access public
     * @param string $name element name
     * @param mixed $value element value
     * @return void
     * @static
     */
    public static function set($name, $value)
    {
        self::configure($name);
        $GLOBALS[$name] = $value;
    }

    /**
     * Get all current values
     *
     * @access public
     * @return array
     * @static
     */
    public static function getAll()
    {
        self::configure();
        return $GLOBALS;
    }

    /**
     * Get component's
     *
     * @access public
     * @param null $name name element to initialize
     * @throws \Micro\base\Exception
     * @static
     */
    public static function configure($name = null)
    {
        if ($name AND isset($GLOBALS[$name])) {
            return;
        }

        if (isset(Micro::getInstance()->config['components'])) {
            $configs = Micro::getInstance()->config['components'];
        } else {
            throw new Exception('Components not configured');
        }

        if ($name AND isset($configs[$name])) {
            if (!self::loadComponent($name, $configs[$name])) {
                throw new Exception('Class ' . $name . ' error loading.');
            }

        } elseif ($name AND !isset($configs[$name])) {
            throw new Exception('Class ' . $name . ' not configured.');

        } else {
            foreach ($configs AS $name => $options) {
                if (!self::loadComponent($name, $options)) {
                    throw new Exception('Class ' . $name . ' error loading.');
                }
            }
        }
    }

    /**
     * Load component
     *
     * @access public
     * @param string $name component name
     * @param array $options component configs
     * @return bool
     * @static
     */
    public static function loadComponent($name, $options)
    {
        if (!isset($options['class']) OR empty($options['class'])) {
            return false;
        }

        if (!class_exists($options['class'])) {
            return false;
        }

        if (isset($options['depends']) AND $options['depends']) {
            if (is_array($options['depends'])) {
                foreach ($options['depends'] AS $depend) {
                    self::configure($depend);
                }
            } else {
                self::configure($options['depends']);
            }
        }

        $className = $options['class'];
        unset($options['class']);

        $GLOBALS[$name] = new $className($options);
        return true;
    }
}