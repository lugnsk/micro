<?php

namespace Micro\File\Drivers;

/**
 * IFile interface for filesystem drivers
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage File\Drivers
 * @version 1.0
 * @since 1.0
 */
interface IFile
{
    /**
     * Create connection stream
     *
     * @access public
     *
     * @return mixed
     */
    public function createStream();

    /**
     * Read connection stream
     *
     * @access public
     *
     * @return mixed
     */
    public function readStream();

    /**
     * Update connection stream
     *
     * @access public
     *
     * @return mixed
     */
    public function updateStream();

    /**
     * Delete connection stream
     *
     * @access public
     *
     * @return mixed
     */
    public function deleteStream();


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
    public function file_get_contents($filePath);

    /**
     * Synonym for file_get_contents
     *
     * @access public
     *
     * @param string $filePath
     *
     * @return mixed
     */
    public function read($filePath);

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
    public function file_put_contents($filePath, $data);

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
    public function copy($sourcePath, $destinationPath);
    //* rename
    //* get
    //* put
    //* write
    //* update
    //* readAndDelete
    //* listContents
    //* touch
    //* createFile
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
    public function file_exists($filePath);

    /**
     * Synonym to file_exists
     *
     * @access public
     *
     * @param string $filePath File path to check
     *
     * @return mixed
     */
    public function exists($filePath);

    /**
     * Synonym to file_exists
     *
     * @access public
     *
     * @param string $filePath File path to check
     *
     * @return mixed
     */
    public function has($filePath);

    /**
     * Unlink file from $filePath
     *
     * @access public
     *
     * @param string $filePath File path
     *
     * @return mixed
     */
    public function unlink($filePath);

    /**
     * Synonym to unlink
     *
     * @access public
     *
     * @param string $filePath File path to delete
     *
     * @return mixed
     */
    public function delete($filePath);


    /**
     * Get free space on dir or filesystem
     *
     * @access public
     *
     * @param string $directory Directory path
     *
     * @return float
     */
    public function disk_free_space($directory);

    /**
     * Get total space on directory or filesystem
     *
     * @access public
     *
     * @param string $directory Directory path
     *
     * @return float
     */
    public function disk_total_space($directory);

    /**
     * Get last modified time
     *
     * @access public
     *
     * @param string $filePath File path
     *
     * @return mixed
     */
    public function mtime($filePath);
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
    /**
     * Get file path size
     *
     * @access public
     * @param string $filePath File path
     * @return mixed
     */
    public function size($filePath);

    /**
     * Synonym for size
     *
     * @access public
     *
     * @param string $filePath File path
     *
     * @return mixed
     */
    public function getSize($filePath);


    // заголовки для работы с директориями
    //* createDir
    //* deleteDir
}
