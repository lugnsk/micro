<?php

namespace App\consoles;

use Micro\base\Command;

class UserCleanerConsoleCommand extends Command
{
    public function execute()
    {
        echo 'Hello, world!', "\n";

        $this->result = true;
    }
}