<?php

namespace App\Http\Utils;

use Illuminate\Http\UploadedFile;

class CsvFileHandler extends FileHandler {

    function __construct(UploadedFile $file)
    {
        if ($file->getMimeType() !== 'text/csv') {
            throw new \Exception("File must be CSV.");
        }
        $this->file = $file;
        $this->originalData = array_map("str_getcsv", explode("\n", $file->getContent()));
        $this->data = $this->formatArrayToJson($this->originalData);
    }

    /*
    * Formats array data from csv to json formatted array
    * Assumes first array is the header column, assigns each row to key/value pairs based on column names
    */
    private function formatArrayToJson($array)
    {
        $headers = $array[0]; // Get the headers from the first array element
        $data = array_slice($array, 1); // Remove the first element (headers) from the array
        $result = [];
    
        foreach ($data as $row) {
            $formattedRow = array_combine($headers, $row); // Combine headers with row data
            $result[] = $formattedRow;
        }
    
        // Not the most efficient, but allows us to have consistent data between file handler types
        return json_decode(json_encode($result));
    }
    
}