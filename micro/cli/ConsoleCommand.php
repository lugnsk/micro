<?php /** MicroConsoleCommand */

namespace Micro\cli;

use Micro\base\Command;
use Micro\web\OutputInterface;

abstract class ConsoleCommand extends Command implements OutputInterface
{
    /** @var int $status Status of running console command */
    protected $status = 0;

    /**
     * Set arguments class
     *
     * @access public
     *
     * @param array $params configuration array
     *
     * @result void
     */
    public function __construct(array $params)
    {
        parent::__construct($params);
    }

    public function send()
    {
        if (!$this->result || $this->status > 0) {
            exit($this->status);
        }

        echo $this->message;
    }
}