<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class misccodes extends Model
{
    //
    protected $fillable=['farmid','parameter', 'season', 'value','active','farmentranceid', 'crop', 'system', 'spacing', 'farmsize', 'farmentranceid'];
}
