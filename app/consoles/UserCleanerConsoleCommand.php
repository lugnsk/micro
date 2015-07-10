<?php

namespace App\consoles;

use Micro\base\ConsoleCommand;

class UserCleanerConsoleCommand extends ConsoleCommand
{
    public function execute()
    {
        $this->message = 'Hello, world!' . "\n";
        $this->result = true;
    }
}