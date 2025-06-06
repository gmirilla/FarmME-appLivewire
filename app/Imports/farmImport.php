<?php

namespace App\Imports;

use App\Models\farm;
use Maatwebsite\Excel\Concerns\ToModel;

class farmImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new farm([
            //
        ]);
    }
}
