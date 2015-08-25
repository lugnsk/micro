<?php /** MicroFile */

namespace Micro\file\drivers;

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
abstract class File implements IFile
{
    /** @var mixed $stream File stream */
    protected $stream;

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
    public function has($filePath)
    {
        return $this->file_exists($filePath);
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
    public function getSize($filePath)
    {
        return $this->size($filePath);
    }

    /**
     * @inheritDoc
     */
    public function delete($filePath)
    {
        return $this->unlink($filePath);
    }
}
