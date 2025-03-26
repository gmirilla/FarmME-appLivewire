<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reports extends Model
{
    //
    protected $fillable = [
        'reportname',
        'reportstate',
        'max_score'
    ];
}
