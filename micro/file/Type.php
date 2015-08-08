<?php /** MicroType */

namespace Micro\file;

/**
 * Class Type
 * @package Micro
 * @subpackage wrappers
 */
class Type
{

    /**
     * Return concrete object type
     *
     * @access public
     *
     * @param mixed $var object to scan
     *
     * @return string
     * @static
     */
    public static function getType($var)
    {
        $type = gettype($var);
        switch ($type) {
            case 'object': {
                return get_class($var);
                break;
            }
            case 'double': {
                return is_float($var) ? 'float' : 'double';
                break;
            }
            default: {
                return strtolower($type);
            }
        }
    }

    /**
     * Get public vars into object
     *
     * @access public
     *
     * @param mixed $object
     *
     * @return array
     * @static
     */
    public static function getVars($object)
    {
        return get_object_vars($object);
    }

    /**
     * Array walk recursive
     *
     * @access public
     *
     * @param $data array to walk
     * @param $function callable function
     *
     * @return array|mixed
     * @static
     */
    public static function arrayWalkRecursive($data, $function)
    {
        if (!is_array($data)) {
            return call_user_func($function, $data);
        }
        foreach ($data as $k => &$item) {
            $item = self::arrayWalkRecursive($data[$k], $function);
        }

        return $data;
    }
}
