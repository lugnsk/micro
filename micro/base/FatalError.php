<?php /** MicroFatalError */

namespace Micro\Base;

/**
 * Class FatalError
 * @package Micro\Base
 */
class FatalError
{
    protected static $number = 0;
    protected static $message = '';
    protected static $file = '';
    protected static $line = 0;
    protected static $context = [];
    protected static $trace = [];

    /**
     * Register FatalError handler
     */
    public static function register()
    {
        set_error_handler(['\Micro\Base\FatalError', 'handle']);
    }

    /**
     * Fatal error handle
     *
     * @param int $number
     * @param string $message
     * @param string $file
     * @param int $line
     * @param array $context
     */
    public static function handle($number = 0, $message = '', $file = '', $line = 0, $context = [])
    {
        self::$context = $context;
        self::$message = $message;
        self::$number = $number;
        self::$trace = debug_backtrace();
        self::$file = $file;
        self::$line = $line;

        $level = ob_get_level();
        if ($level > 0) {
            for ($i = ob_get_level(); $i >= 0; $i--) {
                ob_clean();
            }
        }

//        ob_clean();
//        ob_end_clean();

        print('cli' === php_sapi_name() ? static::doCli() : static::doRun());
    }

    /**
     * @return string
     */
    protected static function doCli()
    {
        return static::$number . ' - ' . static::$message . ' on ' . static::$file . ':' . static::$line;
    }

    /**
     * @return string
     */
    protected static function doRun()
    {
        $str  = '<div class="error" style="width: 100%;">';
            $str .= '<h2>FatalError ' . static::$number . ' - ' . static::$message . ' on ' . static::$file . ':' . static::$line . '</h2>';

            $str .= '<table width="100%" style="width: 100%">';
                $str .= '<tr>';
                    $str .= '<th width="100px">Context</th>';
                    $str .= '<td style="vertical-align: top; height: 300px">';
                        $str .= '<textarea disabled style="width:100%; height: 100%">' . print_r(static::$context, true) . '</textarea>';
                    $str .= '</td>';
                $str .= '</tr>';
                $str .= '<tr>';
                    $str .= '<th width="100px">Debug trace</th>';
                    $str .= '<td style="vertical-align: top; height: 300px">';
                        $str .= '<textarea disabled style="width: 100%; height: 100%">' . print_r(static::$trace, true) . '</textarea>';
                    $str .= '</td>';
                $str .= '</tr>';
            $str .= '</table>';
        $str .= '</div>';

        return $str;
    }
}
