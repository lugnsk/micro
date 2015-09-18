<?php /** MicroLocalDriver */

namespace Micro\file\drivers;

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
     * @inheritdoc
     */
    public function __construct(array $params = [])
    {
        $this->createStream();
    }

    /**
     * @inheritdoc
     */
    public function createStream()
    {
        $this->stream = true;
    }

    /**
     * @inheritdoc
     */
    public function __destruct()
    {
        $this->deleteStream();
    }

    /**
     * @inheritdoc
     */
    public function deleteStream()
    {
        $this->stream = false;
    }

    /**
     * @inheritdoc
     */
    public function file_exists($filePath)
    {
        return \file_exists($filePath);
    }

    /**
     * @inheritdoc
     */
    public function copy($sourcePath, $destinationPath)
    {
        return \copy($sourcePath, $destinationPath);
    }

    /**
     * @inheritdoc
     */
    public function unlink($filePath)
    {
        return \unlink($filePath);
    }

    /**
     * @inheritdoc
     */
    public function file_get_contents($filePath)
    {
        return \file_get_contents($filePath);
    }

    /**
     * @inheritdoc
     */
    public function file_put_contents($filePath, $data)
    {
        return \file_put_contents($filePath, $data);
    }

    /**
     * @inheritdoc
     */
    public function mtime($filePath)
    {
        return \filemtime($filePath);
    }

    /**
     * @inheritdoc
     */
    public function size($filePath)
    {
        return \filesize($filePath);
    }

    /**
     * @inheritdoc
     */
    public function disk_free_space($directory)
    {
        return \disk_free_space($directory);
    }

    /**
     * @inheritdoc
     */
    public function disk_total_space($directory)
    {
        return \disk_total_space($directory);
    }

    /**
     * @inheritdoc
     */
    public function readStream()
    {
        return $this->stream;
    }

    /**
     * @inheritdoc
     */
    public function updateStream()
    {
        $this->stream = true;
    }
}
