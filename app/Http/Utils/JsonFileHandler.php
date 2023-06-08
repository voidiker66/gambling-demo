<?php

namespace App\Http\Utils;

use Illuminate\Http\UploadedFile;

class JsonFileHandler extends FileHandler {
    function __construct(UploadedFile $file)
    {
        if ($file->getMimeType() !== 'application/json') {
            throw new \Exception("File must be JSON.");
        }
        $this->file = $file;
        $this->originalData = $file->getContent();
        $this->data = array_map("json_decode", explode("\n", $this->originalData));
    }
}