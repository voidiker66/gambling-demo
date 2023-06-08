<?php

namespace App\Http\Utils;

use Illuminate\Http\UploadedFile;

abstract class AbstractFileHandler {
    use IterableTrait;

    private UploadedFile $file;
    private mixed $originalData;
    public array $data = [];

    abstract function __construct(UploadedFile $file);
    abstract function getData();
}