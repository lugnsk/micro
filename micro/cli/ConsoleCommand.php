<?php /** MicroConsoleCommand */

namespace Micro\cli;

use Micro\base\Command;
use Micro\web\IOutput;

/**
 * Class ConsoleCommand
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage cli
 * @version 1.0
 * @since 1.0
 * @abstract
 */
abstract class ConsoleCommand extends Command implements IOutput
{
    /** @var int $status Status of running console command */
    protected $status = 0;


    /**
     * @inheritdoc
     */
    public function send()
    {
        if (!$this->result || $this->status > 0) {
            exit($this->status);
        }

        echo $this->message;
    }
}
