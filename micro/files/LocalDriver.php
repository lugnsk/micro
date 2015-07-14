<?php /** MicroLocalDriver */

namespace Micro\files;

/**
 * Class LocalDriver is a driver for local filesystem
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage files
 * @version 1.0
 * @since 1.0
 */
class LocalDriver extends File
{
    /**
     * Create connect to driver FileSystem
     *
     * @access public
     *
     * @param array $params Parameters array
     *
     * @result void
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);

        $this->stream = true;
    }

    /**
     * Destroy connect to driver FileSystem
     */
    public function __destruct()
    {
        unset($this->stream);
    }

    /**
     * Check filePath exists
     *
     * @access public
     *
     * @param string $filePath File path
     *
     * @return bool
     */
    public function file_exists($filePath)
    {
        return \file_exists($filePath);
    }

    /**
     * Copy file from $sourcePath to $destinationPath
     *
     * @access public
     *
     * @param string $sourcePath Path to source file
     * @param string $destinationPath Path to destination file
     *
     * @return bool
     */
    public function copy($sourcePath, $destinationPath)
    {
        return \copy($sourcePath, $destinationPath);
    }

    /**
     * Unlink file from $filePath
     *
     * @access public
     *
     * @param string $filePath File path
     *
     * @return mixed
     */
    public function unlink($filePath)
    {
        return \unlink($filePath);
    }

    /**
     * Get data from file into string
     *
     * @access public
     *
     * @param string $filePath File path
     *
     * @return string
     */
    public function file_get_contents($filePath)
    {
        return \file_get_contents($filePath);
    }

    /**
     * Write any data to file
     *
     * @access public
     *
     * @param string $filePath File path
     * @param mixed $data Data to write
     *
     * @return int
     */
    public function file_put_contents($filePath, $data)
    {
        return \file_put_contents($filePath, $data);
    }

    /**
     * Get free space on dir or filesystem
     *
     * @access public
     *
     * @param string $directory Directory path
     *
     * @return float
     */
    public function disk_free_space($directory)
    {
        return \disk_free_space($directory);
    }

    /**
     * Get total space on directory or filesystem
     *
     * @access public
     *
     * @param string $directory Directory path
     *
     * @return float
     */
    public function disk_total_space($directory)
    {
        return \disk_total_space($directory);
    }
}