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
}
