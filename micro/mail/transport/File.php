<?php

namespace Micro\mail\transport;

use Micro\mail\IMessage;

class File extends Transport
{
    private $mailDir;


    /**
     * @access public
     *
     * @param array $params
     *
     * @result void
     */
    public function __construct(array $params = [])
    {
        $this->mailDir = $params[''] ?: '';
    }

    public function send(IMessage $message)
    {
        //
    }
}
