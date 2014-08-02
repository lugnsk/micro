<?php

namespace Micro\web;

class Uploader
{
    public $files = [];

    public function __construct()
    {
        // проверим есть ли файлы в загрузке
        // посчитаем сколько файлов в загрузке
        if (isset($_FILES)) {
            if (isset($_FILES['name'])) {
                // many files
                $summ = count($_FILES['name']);
                for ($i = 0; $i < $summ; $i++) {
                    if (!isset($_FILES['name'][$i])) {
                        break;
                    }
                    $this->files[] = [
                        'name' => $_FILES['name'][$i],
                        'type' => $_FILES['type'][$i],
                        'error' => $_FILES['error'][$i],
                        'tmp_name' => $_FILES['tmp_name'][$i],
                        'size' => $_FILES['size'][$i]
                    ];
                }
            } else {
                $this->files[] = $_FILES;
            }
        }
    }
}