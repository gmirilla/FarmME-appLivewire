<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class farmunityield extends Model
{
    //
        protected $fillable = [
        'farmid',
        'farmunitid',
        'year',
        'estyield',
        'actualyield',
        'comments',
        'created_by',
        'updated_by'
        
    ];
}
