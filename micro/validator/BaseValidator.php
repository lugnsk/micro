<?php

namespace Micro\validator;

use Micro\base\IContainer;

abstract class BaseValidator extends \stdClass implements IValidator
{
    /** @var string[] $elements */
    public $elements = [];
    /** @var array $errors */
    public $errors = [];
    /** @var IContainer $container */
    protected $container;

    public function __construct(array $params = [])
    {
        foreach ($params AS $key => $val) {
            $this->$key = $val;
        }
    }
}
