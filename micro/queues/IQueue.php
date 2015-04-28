<?php

namespace Micro\queues;


interface IQueue
{
    public function sync( $name, array $params = [], $reply = 10 );
    public function async( $name, array $params = [], $reply = 20 );
    public function stream( $name, array $params = [], $reply = 5 );
}