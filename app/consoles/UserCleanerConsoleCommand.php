<?php

namespace App\Consoles;

use Micro\Cli\ConsoleCommand;

class UserCleanerConsoleCommand extends ConsoleCommand
{
    public function execute()
    {
        $this->message = 'Hello, world!' . "\n";
        $this->result = true;
    }
}
