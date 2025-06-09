<?php

namespace App\Imports;

use App\Models\farm;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class farmImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new farm([
            'farmcode' => $row['farmcode'],
            'village' => $row['village'],
            'state' => $row['state'],	
            'region' => $row['region'],
            'measeaurement' => $row['measurement'],
            'farmcode' => $row['farmcode'],
            'yearofcertification' => $row['certification'],
            'fname' => $row['fname'],
            'surname' => $row['surname'],
            'phonenumber' => $row['phonenumber'],  
            'nationalidnumber' => $row['nationalidnumber'],
            'gender' => $row['gender'],
            'community' => $row['community'],
            'farmname'=>$row['farmname'],
            'farmstate'=>$row['farmstatus']

            //
        ]);
    }
}