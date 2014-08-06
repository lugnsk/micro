<?php /** MicroAutoloader */

namespace Micro\base;

/**
 * MAutoload class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class Autoload
{
    private static $aliases = [];

    public static function setAlias($alias, $realPath)
    {
        self::$aliases[$alias] = $realPath;
    }

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
                $path .= (isset(self::$aliases[$alias])) ? self::$aliases[$alias] : '';
                $className = substr($className, $firstNsPos);
                $lastNsPos -= $firstNsPos;
            }

            // Get namespace and class name
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            // Add in path
            $path .= strtr($namespace, '\\', DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }

        // result path
        $path .= strtr($className, '_', DIRECTORY_SEPARATOR) . '.php';
        require $path;
    }
}