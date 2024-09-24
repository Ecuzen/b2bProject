<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class YourImportClass implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Process each row of the Excel file
        foreach ($rows as $row) {
            // Process each row as needed
            // For example, you can store it in the database or perform some calculations
        }
    }
}