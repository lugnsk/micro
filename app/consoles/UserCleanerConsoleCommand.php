<?php

namespace App\Consoles;

use Micro\Cli\ConsoleCommand;

/**
 * Class UserCleanerConsoleCommand
 * @package App\Consoles
 */
class UserCleanerConsoleCommand extends ConsoleCommand
{
    /**
     *
     */
    public function execute()
    {
        $this->message = 'Hello, world!' . "\n";
        $this->result = true;
    }
}
