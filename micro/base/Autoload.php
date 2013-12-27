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
        // Patch prev backslash
        $className = ltrim($className, '\\');
        // Define result path
        $path = '';
        // if use namespace
        if ($lastNsPos = strrpos($className, '\\')) {
            // Get alias
            $firstNsPos = strpos($className, '\\');
            // Add alias in path
            if ($alias = substr($className, 0, $firstNsPos)) {
                $path .= !empty(self::$aliases[$alias]) ? self::$aliases[$alias] : '';
                $className = substr($className, $firstNsPos);
                $lastNsPos -= $firstNsPos;
            }
            // Add in path
            $path .= strtr(substr($className, 0, $lastNsPos), '\\', DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
        // result path
        $path .= strtr(substr($className, $lastNsPos + 1), '_', DIRECTORY_SEPARATOR) . '.php';

        if (is_file($path)) {
            require_once $path;
            return true;
        }
        return false;
    }
}