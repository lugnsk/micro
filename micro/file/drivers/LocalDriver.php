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
     * @inheritDoc
     */
    public function __construct(array $params = [])
    {
        $this->createStream();
    }

    /**
     * @inheritDoc
     */
    public function createStream()
    {
        $this->stream = true;
    }

    /**
     * @inheritDoc
     */
    public function __destruct()
    {
        $this->deleteStream();
    }

    /**
     * @inheritDoc
     */
    public function deleteStream()
    {
        $this->stream = false;
    }

    /**
     * @inheritDoc
     */
    public function exists($filePath)
    {
        return $this->file_exists($filePath);
    }

    /**
     * @inheritDoc
     */
    public function file_exists($filePath)
    {
        return \file_exists($filePath);
    }

    /**
     * @inheritDoc
     */
    public function has($filePath)
    {
        return $this->file_exists($filePath);
    }

    /**
     * @inheritDoc
     */
    public function copy($sourcePath, $destinationPath)
    {
        return \copy($sourcePath, $destinationPath);
    }

    /**
     * @inheritDoc
     */
    public function delete($filePath)
    {
        return $this->unlink($filePath);
    }

    /**
     * @inheritDoc
     */
    public function unlink($filePath)
    {
        return \unlink($filePath);
    }

    /**
     * @inheritDoc
     */
    public function read($filePath)
    {
        return $this->file_get_contents($filePath);
    }

    /**
     * @inheritDoc
     */
    public function file_get_contents($filePath)
    {
        return \file_get_contents($filePath);
    }

    /**
     * @inheritDoc
     */
    public function mtime($filePath)
    {
        return \filemtime($filePath);
    }

    /**
     * @inheritDoc
     */
    public function getSize($filePath)
    {
        return $this->size($filePath);
    }

    /**
     * @inheritDoc
     */
    public function size($filePath)
    {
        return \filesize($filePath);
    }

    /**
     * @inheritDoc
     */
    public function readStream()
    {
        return $this->stream;
    }

    /**
     * @inheritDoc
     */
    public function updateStream()
    {
        $this->stream = true;
    }

    /**
     * @inheritDoc
     */
    public function file_put_contents($filePath, $data)
    {
        return \file_put_contents($filePath, $data);
    }

    /**
     * @inheritDoc
     */
    public function disk_free_space($directory)
    {
        return \disk_free_space($directory);
    }

    /**
     * @inheritDoc
     */
    public function disk_total_space($directory)
    {
        return \disk_total_space($directory);
    }
}
