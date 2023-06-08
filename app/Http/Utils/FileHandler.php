<?php

namespace App\Http\Utils;

use Illuminate\Http\UploadedFile;

class FileHandler extends AbstractFileHandler {
    function __construct(UploadedFile $file)
    {
        $this->file = $file;
        $this->originalData = $file->getContent();
        $this->data = $this->originalData;
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}