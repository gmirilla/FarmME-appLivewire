<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class othercropsrecords extends Model
{
    //
    protected $fillable=[
        'season','plotname','crop', 'area', 'location','active','farmid'
    ];
}
