<?php /** MicroDefaultConsoleCommand */

namespace Micro\Cli\Consoles;

use Micro\Cli\ConsoleCommand;

/**
 * Class DefaultConsoleCommand
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Cli
 * @version 1.0
 * @since 1.0
 * @abstract
 */
class DefaultConsoleCommand extends ConsoleCommand
{
    public $data;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->result = true;
        $this->message = $this->data;
    }
}
