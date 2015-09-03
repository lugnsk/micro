<?php /** MicroAutoloader */

namespace Micro\base;

/**
 * Autoload class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class Autoload
{
    /** @var array $aliases aliases for base dirs */
    private static $aliases = [];

    /**
     * Setting or installing new alias
     *
     * @access public
     *
     * @param string $alias name for new alias
     * @param string $realPath path of alias
     *
     * @return void
     * @static
     */
    public static function setAlias($alias, $realPath)
    {
        self::$aliases[$alias] = $realPath;
    }

    /**
     * Loader classes
     *
     * @access public
     *
     * @param string $className search class name
     *
     * @return bool
     * @static
     */
    public static function loader($className)
    {
        $path = self::getClassPath($className);

        if (is_file($path)) {
            /** @noinspection PhpIncludeInspection */
            require_once $path;

            return true;
        }

        return false;
    }

    /**
     * Get class path
     *
     * @access public
     *
     * @param string $className search class name
     *
     * @return string
     * @static
     */
    public static function getClassPath($className)
    {
        $className = ltrim($className, '\\');

        $path = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $firstNsPos = strpos($className, '\\');
            if ($alias = substr($className, 0, $firstNsPos)) {
                $path .= !empty(self::$aliases[$alias]) ? self::$aliases[$alias] : '';

                $className = substr($className, $firstNsPos);
                $lastNsPos -= $firstNsPos;
            }
            $path .= str_replace('\\', DIRECTORY_SEPARATOR, substr($className, 0, $lastNsPos)) . DIRECTORY_SEPARATOR;
        }

        return $path . str_replace('_', DIRECTORY_SEPARATOR, substr($className, $lastNsPos + 1)) . '.php';
    }

    /**
     * Loader classes
     *
     * @access public
     *
     * @param string $className search class name
     *
     * @return bool
     * @static
     */
    public static function loader($className)
    {
        $path = self::getClassPath($className);

        if (is_file($path)) {
            /** @noinspection PhpIncludeInspection */
            require_once $path;

            return true;
        }

        return false;
    }
}
