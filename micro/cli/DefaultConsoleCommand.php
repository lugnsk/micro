<?php

namespace Micro\cli;

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
