<?php

namespace Micro\mvc;


abstract class Action
{
    /**
     * Running action
     *
     * @access public
     *
     * @return mixed
     */
    abstract public function run();
}