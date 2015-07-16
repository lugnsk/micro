<?php /** MicroFile */

namespace Micro\files;

/**
 * Class File is interface for filesystem drivers
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
abstract class File
{
    protected $stream;


    // Заголовки для работы с потоком
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
    }

    /**
     * Destroy connect to driver FileSystem
     *
     * @access public
     *
     * @result void
     * @abstract
     */
    abstract public function __destruct();
    //* createStream
    //* readStream
    //* writeStream
    //* updateStream
    //* putStream


    // заголовки для работы с файлами
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
    abstract public function copy($sourcePath, $destinationPath);

    /**
     * Unlink file from $filePath
     *
     * @access public
     *
     * @param string $filePath File path
     *
     * @return mixed
     */
    abstract public function unlink($filePath);

    /**
     * Check filePath exists
     *
     * @access public
     *
     * @param string $filePath File path
     *
     * @return bool
     * @abstract
     */
    abstract public function file_exists($filePath);

    /**
     * Get data from file into string
     *
     * @access public
     *
     * @param string $filePath File path
     *
     * @return string
     * @abstract
     */
    abstract public function file_get_contents($filePath);

    /**
     * Write any data to file
     *
     * @access public
     *
     * @param string $filePath File path
     * @param mixed $data Data to write
     *
     * @return int
     * @abstract
     */
    abstract public function file_put_contents($filePath, $data);
    //* exists
    //* has
    //* rename
    //* get
    //* put
    //* write
    //* update
    //* copy
    //* read
    //* delete
    //* getSize
    //* size
    //* readAndDelete
    //* listcontents
    //* touch
    //* createFile


    // заголовки для работы с метой
    /**
     * Get free space on dir or filesystem
     *
     * @access public
     *
     * @param string $directory Directory path
     *
     * @return float
     */
    abstract public function disk_free_space($directory);

    /**
     * Get total space on directory or filesystem
     *
     * @access public
     *
     * @param string $directory Directory path
     *
     * @return float
     */
    abstract public function disk_total_space($directory);
    //* mtime
    //* getMimeType
    //* getMetaData
    //* getAccessTime
    //* setAccessTime
    //* getTimestamp
    //* getVisibility
    //* setVisibility
    //* getGroup
    //* setGroup
    //* mimeType
    //* checksum


    // заголовки для работы с директориями
    //* createDir
    //* deleteDir
}