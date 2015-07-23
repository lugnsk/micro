<?php

namespace Micro\validator;

use Micro\base\Container;

abstract class BaseValidator extends \stdClass implements IValidator
{
    /** @var string[] $elements */
    public $elements = [];
    /** @var Container $container */
    protected $container;

    public function __construct(array $params = [])
    {
        foreach ($params AS $key => $val) {
            $this->$key = $val;
        }
    }
}