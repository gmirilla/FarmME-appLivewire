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
            'community' => $row['region'],
            'measeaurement' => $row['measurement'],
            'farmcode' => $row['farmcode'],
            'fname' => $row['fname'],
            'surname' => $row['surname'],
            'phonenumber' => $row['phonenumber'],  
            'nationalidnumber' => $row['nationalidnumber'],
            'gender' => $row['gender'],
            'farmname'=>$row['farmname'],
            'farmstate'=>$row['farmstate'],
            'measurement'=>$row['measurement'],
            'yob'=>$row['yob'],
            'farmarea'=>$row['farmarea'],
            'farmtype'=>$row['farmtype'],
            'crop'=>$row['crop'],
            'cropvariety'=>$row['cropvariety']

            //
        ]);
    }
}