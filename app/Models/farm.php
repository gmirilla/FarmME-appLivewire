<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class farm extends Model
{
    //Hold the Basic details of the Farm
    protected $fillable = [
        'farmerName',
        'community',
        'farmCode',
        'farmstate',
        'lastinspection',
        'nextinspection',
        'latitude',
        'longitude',
        'farmarea',
        'inspectorid',
        'measurement'

    ];
}
