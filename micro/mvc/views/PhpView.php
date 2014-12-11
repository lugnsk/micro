<?php

class PhpView {
    public $layout;
    public $path;

    public $data=[];

    public function __get($name) {
        return $this->data[$name];
    }
    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    public function render() {
        //
    }
}