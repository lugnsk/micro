<?php /** MicroFileHelper */

namespace Micro\File;

use Micro\Base\Exception;

/**
 * MFile io class
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage File
 * @version 1.0
 * @since 1.0
 */
class FileHelper
{
    /**
     * Directory size
     *
     * @access public
     *
     * @param string $dirName directory name
     *
     * @return integer
     * @static
     */
    public static function dirSize($dirName)
    {
        $totalSize = 0;
        if ($dirStream = opendir($dirName)) {
            while (false !== ($fileName = readdir($dirStream))) {
                if ($fileName !== '.' && $fileName !== '..') {
                    if (is_file($dirName . '/' . $fileName)) {
                        $totalSize += filesize($dirName . '/' . $fileName);
                    }

                    if (is_dir($dirName . '/' . $fileName)) {
                        $totalSize += self::dirSize($dirName . '/' . $fileName);
                    }
                }
            }
        }

        closedir($dirStream);

        return $totalSize;
    }

    /**
     * Recursive remove dir
     *
     * @access public
     *
     * @param string $path path to remove
     *
     * @return void
     * @static
     */
    public static function removeDir($path)
    {
        if (is_file($path)) {
            unlink($path);
        } else {
            foreach (scandir($path) as $dir) {
                if ($dir === '.' || $dir === '..') {
                    continue;
                }

                static::removeDir($path . '/' . $dir);
            }

            rmdir($path);
        }
    }

    /**
     * Recursive copy files
     *
     * @access public
     *
     * @param string $src source path
     * @param string $dst destination path
     *
     * @return void
     * @throws Exception
     * @static
     */
    public static function recurseCopy($src, $dst)
    {
        $dir = opendir($src);
        if (!@mkdir($dst, 0777) && !is_dir($dst)) {
            throw new Exception('Copy dir error, access denied for path: ' . $dst);
        }

        while (false !== ($file = readdir($dir))) {
            if (($file !== '.') && ($file !== '..')) {
                if (is_dir($src . '/' . $file)) {
                    self::recurseCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                    chmod($dst . '/' . $file, 0666);
                }
            }
        }
        closedir($dir);
    }

    /**
     * Recursive copy files if edited
     *
     * @access public
     *
     * @param string $src source path
     * @param string $dst destination path
     * @param array $excludes excludes extensions
     *
     * @return void
     * @throws Exception
     * @static
     */
    public static function recurseCopyIfEdited($src = '', $dst = '', array $excludes = ['php'])
    {
        if (!@mkdir($dst, 0777) && !is_dir($dst)) {
            throw new Exception('Copy dir error, access denied for path: ' . $dst);
        }

        $dir = opendir($src);
        if (!$dir) {
            throw new Exception('Unable to read dir: '. $src);
        }

        while (false !== ($file = readdir($dir))) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir($src . '/' . $file)) {
                self::recurseCopyIfEdited($src . '/' . $file, $dst . '/' . $file, $excludes);
                continue;
            }

            if (in_array(substr($file, strrpos($file, '.') + 1), $excludes, null)) {
                continue;
            }


            if (file_exists($dst . '/' . $file) && (filemtime($src . '/' . $file) === filemtime($dst . '/' . $file))) {
                continue;
            }

            copy($src . '/' . $file, $dst . '/' . $file);
            chmod($dst . '/' . $file, 0666);
        }
        closedir($dir);
    }
}
